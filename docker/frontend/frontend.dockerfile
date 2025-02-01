# Build stage
FROM node:22.12-alpine as builder

ARG NUXT_TOKEN_STORAGE_PROVIDER
ARG NUXT_SANCTUM_API_URL
ARG NUXT_AUTH_MODE

ENV NUXT_TOKEN_STORAGE_PROVIDER=$NUXT_TOKEN_STORAGE_PROVIDER \
    NUXT_SANCTUM_API_URL=$NUXT_SANCTUM_API_URL \
    NUXT_AUTH_MODE=$NUXT_AUTH_MODE

WORKDIR /builder

COPY ./src/frontend/package*.json ./
RUN npm install

COPY ./src/frontend .
RUN npm run generate

# Serve stage
FROM nginx:alpine

COPY --from=builder /builder/.output/public /usr/share/nginx/html

RUN rm /etc/nginx/conf.d/default.conf
COPY ./docker/frontend/frontend-nginx.conf /etc/nginx/conf.d/default.conf

EXPOSE 80

CMD ["nginx", "-g", "daemon off;"]