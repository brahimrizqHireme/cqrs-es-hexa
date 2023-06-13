FROM mongo:6.0

# Copy the MongoDB configuration file to the container
COPY ./.docker/mongodb/mongod.conf /etc/mongod.conf

# Expose the MongoDB default port
EXPOSE 27017

# Start MongoDB with custom configuration
CMD ["mongod", "--config", "/etc/mongod.conf"]
