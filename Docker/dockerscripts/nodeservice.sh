#!/bin/sh
# npm / yarn install and run dev
export NODE_ENV=development
echo 'running npm install and dev for changes'
npm install && npm run dev
