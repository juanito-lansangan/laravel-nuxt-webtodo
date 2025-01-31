# Build stage
FROM node:22.12-alpine as builder

WORKDIR /builder

COPY ./src/frontend/package*.json ./
RUN npm install

COPY ./src/frontend .
RUN npm run generate

# Serve stage
FROM nginx:alpine

COPY --from=builder /builder/.output/public /usr/share/nginx/html

RUN rm /etc/nginx/conf.d/default.conf
COPY ./docker/config/frontend-nginx.conf /etc/nginx/conf.d/default.conf

EXPOSE 80

CMD ["nginx", "-g", "daemon off;"]