# Dockerfile for creating a vue3 app with vite
FROM oven/bun:1.1.26-alpine AS build
# Create working directory
WORKDIR /srv/public/
# Copy the source code
COPY . .
# Install dependencies
RUN bun install
# Expose port
EXPOSE 80

# Run dev
FROM build AS development
CMD bun run dev

# Run prod
FROM build AS production
CMD bun run build && bun run preview
