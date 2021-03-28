FROM httpd:2.4

ARG USER_ID
ARG GROUP_ID

RUN groupmod -g ${GROUP_ID} www-data && usermod -u ${USER_ID} www-data
