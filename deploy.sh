#!/bin/bash
# MTS Hospital - Deployment Script

echo "🏥 MTS Hospital Deployment Script"
echo "=================================="
echo ""

# Colors for output
GREEN='\033[0;32m'
BLUE='\033[0;34m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m' # No Color

# Function to display menu
show_menu() {
    echo ""
    echo "Select deployment platform:"
    echo "1) Heroku"
    echo "2) Railway"
    echo "3) Git push only (for manual deployment)"
    echo "4) Create deployment package (ZIP)"
    echo "5) Exit"
    echo ""
    read -p "Enter your choice [1-5]: " choice
}

# Function to check if git is initialized
check_git() {
    if [ ! -d .git ]; then
        echo -e "${YELLOW}Git repository not initialized.${NC}"
        read -p "Initialize git now? (y/n): " init_git
        if [ "$init_git" = "y" ]; then
            git init
            git add .
            git commit -m "Initial commit: MTS Hospital website"
            echo -e "${GREEN}✓ Git initialized${NC}"
        else
            echo -e "${RED}Cannot proceed without git${NC}"
            exit 1
        fi
    fi
}

# Function to deploy to Heroku
deploy_heroku() {
    echo ""
    echo -e "${BLUE}Deploying to Heroku...${NC}"
    
    # Check if Heroku CLI is installed
    if ! command -v heroku &> /dev/null; then
        echo -e "${RED}✗ Heroku CLI not installed${NC}"
        echo "Install from: https://devcenter.heroku.com/articles/heroku-cli"
        exit 1
    fi
    
    # Check if logged in
    if ! heroku auth:whoami &> /dev/null; then
        echo "Please login to Heroku..."
        heroku login
    fi
    
    # Check if app exists
    read -p "Enter Heroku app name (or press Enter to create new): " app_name
    
    if [ -z "$app_name" ]; then
        echo "Creating new Heroku app..."
        heroku create
    else
        # Try to add remote
        if ! git remote get-url heroku &> /dev/null; then
            heroku git:remote -a "$app_name"
        fi
    fi
    
    # Deploy
    echo "Pushing to Heroku..."
    git add .
    git commit -m "Deploy to Heroku" --allow-empty
    git push heroku main || git push heroku master
    
    echo -e "${GREEN}✓ Deployed to Heroku!${NC}"
    echo "Opening app..."
    heroku open
}

# Function to deploy to Railway
deploy_railway() {
    echo ""
    echo -e "${BLUE}Deploying to Railway...${NC}"
    
    if ! command -v railway &> /dev/null; then
        echo -e "${YELLOW}Railway CLI not installed${NC}"
        echo "Install from: https://docs.railway.app/develop/cli"
        echo ""
        echo "Alternative: Deploy via Railway web dashboard"
        echo "1. Go to https://railway.app"
        echo "2. Click 'New Project'"
        echo "3. Select 'Deploy from GitHub repo'"
        echo "4. Choose your repository"
        echo ""
        read -p "Press Enter to continue..."
        return
    fi
    
    # Check if logged in
    if ! railway whoami &> /dev/null; then
        echo "Please login to Railway..."
        railway login
    fi
    
    # Deploy
    echo "Deploying to Railway..."
    railway up
    
    echo -e "${GREEN}✓ Deployed to Railway!${NC}"
}

# Function to git push
git_push() {
    echo ""
    echo -e "${BLUE}Pushing to Git...${NC}"
    
    # Check for remote
    if ! git remote get-url origin &> /dev/null; then
        echo -e "${YELLOW}No git remote configured${NC}"
        read -p "Enter GitHub repository URL: " repo_url
        git remote add origin "$repo_url"
    fi
    
    # Push
    git add .
    read -p "Enter commit message: " commit_msg
    if [ -z "$commit_msg" ]; then
        commit_msg="Update deployment files"
    fi
    
    git commit -m "$commit_msg"
    git push -u origin main || git push -u origin master
    
    echo -e "${GREEN}✓ Pushed to GitHub!${NC}"
}

# Function to create deployment package
create_package() {
    echo ""
    echo -e "${BLUE}Creating deployment package...${NC}"
    
    timestamp=$(date +%Y%m%d_%H%M%S)
    package_name="mts-hospital-deploy-${timestamp}.zip"
    
    # Files to include
    zip -r "$package_name" \
        index.php \
        test_language.php \
        .htaccess \
        composer.json \
        Procfile \
        README.md \
        GITHUB_DEPLOYMENT.md \
        DEPLOYMENT_GUIDE.md \
        START_HERE.md \
        TROUBLESHOOTING.md \
        -x "*.git*" "*.DS_Store"
    
    echo -e "${GREEN}✓ Package created: ${package_name}${NC}"
    echo ""
    echo "Upload this package to your hosting provider:"
    echo "  - InfinityFree: Use File Manager"
    echo "  - 000webhost: Use File Manager"
    echo "  - cPanel: Use File Manager or FTP"
    echo ""
}

# Main script
main() {
    # Check if in project directory
    if [ ! -f "index.php" ]; then
        echo -e "${RED}Error: index.php not found${NC}"
        echo "Please run this script from the project root directory"
        exit 1
    fi
    
    # Check git
    check_git
    
    # Show menu
    show_menu
    
    case $choice in
        1)
            deploy_heroku
            ;;
        2)
            deploy_railway
            ;;
        3)
            git_push
            ;;
        4)
            create_package
            ;;
        5)
            echo "Goodbye!"
            exit 0
            ;;
        *)
            echo -e "${RED}Invalid choice${NC}"
            exit 1
            ;;
    esac
    
    echo ""
    echo -e "${GREEN}==================================${NC}"
    echo -e "${GREEN}Deployment Complete!${NC}"
    echo -e "${GREEN}==================================${NC}"
    echo ""
    echo "Next steps:"
    echo "1. Test language switching"
    echo "2. Access test_language.php for testing"
    echo "3. Monitor for any errors"
    echo ""
    echo "See GITHUB_DEPLOYMENT.md for more information"
}

# Run main function
main
