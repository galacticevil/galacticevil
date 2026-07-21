FROM php:8.3-apache

RUN apt-get update \
    && apt-get install -y --no-install-recommends ca-certificates msmtp-mta \
    && rm -rf /var/lib/apt/lists/*

COPY config/msmtprc /etc/msmtprc
COPY config/contact-mail.ini /usr/local/etc/php/conf.d/contact-mail.ini
COPY docker-entrypoint.sh /usr/local/bin/contact-entrypoint

RUN chmod 0644 /etc/msmtprc /usr/local/etc/php/conf.d/contact-mail.ini \
    && chmod 0755 /usr/local/bin/contact-entrypoint

ENTRYPOINT ["contact-entrypoint"]
CMD ["apache2-foreground"]
