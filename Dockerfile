# Use Node.js LTS image
FROM node:18-alpine

# Set working directory
WORKDIR /app

# Copy package.json and install dependencies
COPY package*.json ./
RUN npm install --production

# Copy all source files
COPY . .

# Expose port (e.g., 3000)
EXPOSE 3000

# Start the app
CMD ["npm", "start"]
