**MintHCM Docker**

This repository provides a comprehensive Docker Compose setup for deploying MintHCM, a robust CRM application, in a containerized environment. MintHCM is ready to go with this easy-to-use Docker configuration.

**Features:**

- **Containerized Deployment:** Deploy MintHCM effortlessly using Docker Compose. The setup includes the MintHCM web application, a MySQL database container and an Elastic Search container.

- **Configuration Options:** Customize your MintHCM installation by adjusting variables in the `.env` file. Modify parameters like the web port, database details, MintHCM admin credentials etc.

**Instructions:**

1. Download [docker-compose.yml](https://raw.githubusercontent.com/minthcm/minthcm/master/docker/docker-compose.yml) and [.env](https://raw.githubusercontent.com/minthcm/minthcm/master/docker/.env):

```
curl -sSL https://raw.githubusercontent.com/minthcm/minthcm/master/docker/docker-compose.yml
curl -sSL https://raw.githubusercontent.com/minthcm/minthcm/master/docker/.env
```

2. Customize Settings:
- Edit the `.env` file.

3. Launch MintHCM:
```
docker compose up -d
```

4. Monitor Progress:
```
docker compose logs -f
```
After successful installation, you should see:
```
 [OK] Installation finished successfuly
```

5. Access MintHCM:
- Open your web browser and access MintHCM at `http://localhost` or the configured IP and port.

6. Configuration Changes:
- If you wish to make changes to the `.env` file, create a fresh environment to ensure MintHCM is installed with the new parameters.

7. Enjoy MintHCM:
- Log in to MintHCM using the admin credentials specified in the `.env` file and start managing your CRM operations. 
Default:
login: admin
password: minthcm

⚠️ **Important:** This Docker Compose setup is intended for demonstration and testing purposes only. We do not recommend using this configuration in a production environment.

**Note:**
- Docker and Docker Compose must be installed on your system.

Docker Hub: https://hub.docker.com/repository/docker/minthcm/minthcm/general
