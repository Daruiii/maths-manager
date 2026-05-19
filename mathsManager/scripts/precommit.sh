#!/bin/bash

# Maths Manager - Pre-commit verification script
# Usage: ./scripts/precommit.sh

echo "🔍 Maths Manager Pre-commit Check"
echo "=============================="
echo ""

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

ERRORS=0

# Se placer dans le répertoire racine du projet
cd "$(dirname "$0")/.."

# 1. TypeScript type checking
echo "📘 Running TypeScript check..."
if npm run tsc; then
    echo -e "${GREEN}✓ TypeScript OK${NC}"
    echo ""
else
    echo -e "${RED}✗ TypeScript errors found${NC}"
    echo ""
    ERRORS=$((ERRORS + 1))
fi

# 2. Linting
if grep -q '"lint"' package.json 2>/dev/null; then
    echo "🔎 Running linter..."
    if npm run lint; then
        echo -e "${GREEN}✓ Lint OK${NC}"
        echo ""
    else
        echo -e "${RED}✗ Lint errors found${NC}"
        echo ""
        ERRORS=$((ERRORS + 1))
    fi
else
    echo -e "${YELLOW}⚠️  No lint script found (skip)${NC}"
    echo ""
fi

# 3. Prettier format check
if grep -q '"prettier:check"' package.json 2>/dev/null; then
    echo "💅 Checking code formatting..."
    if npm run prettier:check; then
        echo -e "${GREEN}✓ Formatting OK${NC}"
        echo ""
    else
        echo -e "${RED}✗ Formatting issues found. Run: npm run prettier:fix${NC}"
        echo ""
        ERRORS=$((ERRORS + 1))
    fi
else
    echo -e "${YELLOW}⚠️  No prettier:check script found (skip)${NC}"
    echo ""
fi

# 4. React tests (Vitest)
if grep -q '"test:react"' package.json 2>/dev/null; then
    echo "⚛️  Running React tests..."
    if npm run test:react; then
        echo -e "${GREEN}✓ React tests OK${NC}"
        echo ""
    else
        echo -e "${RED}✗ React tests failed${NC}"
        echo ""
        ERRORS=$((ERRORS + 1))
    fi
else
    echo -e "${YELLOW}⚠️  No test:react script found (skip)${NC}"
    echo ""
fi

# 5. PHP tests (safe testing env)
echo "🐘 Running PHP tests..."
if php artisan config:clear && php artisan test --env=testing; then
    echo -e "${GREEN}✓ PHP tests OK${NC}"
    echo ""
else
    echo -e "${RED}✗ PHP tests failed${NC}"
    echo ""
    ERRORS=$((ERRORS + 1))
fi

# Summary
echo "=============================="
if [ $ERRORS -eq 0 ]; then
    echo -e "${GREEN}✅ All checks passed! Ready to commit.${NC}"
    exit 0
else
    echo -e "${RED}❌ $ERRORS error(s) found. Fix before committing.${NC}"
    exit 1
fi
