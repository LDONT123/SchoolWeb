# 校园信息门户网站 - 后端 API

本项目是为“昆明市盘龙区财大附中”校园信息门户网站创建的后端 API。它提供了对活动、新闻、重要日期、教职工、校园资源、图库图片和文档资料等数据实体的管理功能。

## 环境要求

在部署或运行此后端 API 之前，请确保您的环境中满足以下要求：

1.  **Node.js**:
    *   建议使用 Node.js LTS 版本 (例如：v18.x 或 v20.x)。
    *   您可以从 [Node.js 官网](https://nodejs.org/) 下载并安装。
    *   安装 Node.js 的同时会自动安装 npm (Node Package Manager)。如果您偏好使用 Yarn，请自行安装。

2.  **MongoDB**:
    *   本应用使用 MongoDB作为数据库。
    *   **开发环境**: 您可以在本地安装 MongoDB Community Server。
    *   **生产环境**: 强烈建议使用云 MongoDB 服务，如 [MongoDB Atlas](https://www.mongodb.com/cloud/atlas)，以确保数据可靠性和易管理性。
    *   确保您的 MongoDB 服务正在运行并且网络可访问。

3.  **Git**:
    *   用于克隆代码仓库。

## 本地开发与运行指南

按照以下步骤在您的本地计算机上设置并运行后端 API：

1.  **克隆代码仓库**:
    ```bash
    git clone <仓库URL>
    cd <项目目录>
    ```

2.  **安装依赖**:
    使用 npm:
    ```bash
    npm install
    ```
    或者使用 yarn:
    ```bash
    yarn install
    ```

3.  **配置环境变量**:
    在项目根目录下创建一个 `.env` 文件。此文件用于存储敏感配置，**不应**提交到 Git 仓库 (已在 `.gitignore` 中配置忽略)。
    复制以下内容到 `.env` 文件，并根据您的本地环境进行修改：

    ```env
    # .env 文件示例
    PORT=3000
    MONGODB_URI=mongodb://localhost:27017/campus_portal_db_dev 
    # NODE_ENV=development (可选, 通常在启动脚本中设置)
    ```
    *   `PORT`: API 服务监听的端口号。
    *   `MONGODB_URI`: 您的本地 MongoDB 连接字符串。`campus_portal_db_dev` 是建议的开发数据库名称。

4.  **启动开发服务器**:
    此命令会使用 `nodemon` 启动服务器，它会在代码更改时自动重启，非常适合开发。
    ```bash
    npm run dev
    ```
    如果一切正常，您应该会在控制台看到类似以下的输出：
    ```
    Server started on port 3000
    MongoDB Connected...
    ```
    现在 API 应该在 `http://localhost:3000` 上运行。

5.  **运行普通启动 (不使用 nodemon)**:
    ```bash
    npm start
    ```

## 生产环境部署指南

将此 API 部署到生产环境需要更细致的配置以确保性能、安全性和可靠性。

1.  **代码准备**:
    *   确保您已将最新的稳定代码推送到您的 Git 仓库。
    *   在服务器上克隆或拉取最新代码。

2.  **安装生产依赖**:
    在服务器上，只安装生产所需的依赖项：
    ```bash
    npm install --production
    ```

3.  **配置生产环境变量**:
    在生产服务器上，**强烈建议**通过操作系统环境变量或部署平台的配置服务来设置以下变量，而不是使用 `.env` 文件：
    *   `NODE_ENV=production`: 此变量会启用 Express 和其他库的生产模式优化。
    *   `PORT`: 生产环境的监听端口 (例如 80, 443, 或由 PaaS 平台提供)。
    *   `MONGODB_URI`: **必须**指向您的生产 MongoDB 数据库 (例如 MongoDB Atlas 的连接字符串)。**切勿在生产中使用开发数据库！**
    *   `JWT_SECRET` (如果未来添加认证功能): 用于签署 JWT 的密钥。
    *   其他任何 API 密钥或敏感配置。

4.  **进程管理器 (Process Manager)**:
    在生产环境中，您不应直接使用 `node server.js` 运行应用。应使用进程管理器，如 PM2，它可以：
    *   在应用崩溃时自动重启。
    *   管理日志。
    *   实现零停机重载。
    *   监控应用性能。

    安装 PM2:
    ```bash
    npm install -g pm2
    ```
    使用 PM2 启动应用 (在项目根目录运行):
    ```bash
    pm2 start server.js --name "campus-api" --env production
    ```
    常用 PM2 命令:
    *   `pm2 list`: 查看所有正在运行的应用。
    *   `pm2 logs campus-api`: 查看 "campus-api" 的日志。
    *   `pm2 restart campus-api`: 重启应用。
    *   `pm2 stop campus-api`: 停止应用。
    *   `pm2 delete campus-api`: 删除应用。
    *   `pm2 startup` 和 `pm2 save`: 配置 PM2 开机自启。

5.  **反向代理 (Reverse Proxy)**:
    建议在 Node.js 应用前设置一个反向代理服务器，如 Nginx 或 Apache。它可以：
    *   处理 SSL/TLS (HTTPS)。
    *   进行负载均衡 (如果有多台服务器)。
    *   提供静态文件服务。
    *   进行请求缓存。
    *   增加一层安全防护。

    Nginx 配置示例 (简化版，监听 80 端口并转发到 Node 应用的 3000 端口):
    ```nginx
    server {
        listen 80;
        server_name your_domain.com; # 替换为您的域名

        location / {
            proxy_pass http://localhost:3000; # 假设 Node 应用在同一台服务器的 3000 端口
            proxy_http_version 1.1;
            proxy_set_header Upgrade $http_upgrade;
            proxy_set_header Connection 'upgrade';
            proxy_set_header Host $host;
            proxy_cache_bypass $http_upgrade;
        }
    }
    ```
    您还需要使用 Certbot 等工具为您的域名配置免费的 Let's Encrypt SSL 证书以启用 HTTPS。

6.  **安全性增强**:
    *   **HTTPS**: 始终在生产中使用 HTTPS。
    *   **CORS**: 如果前端和后端部署在不同域名，请在 `server.js` 中配置 `cors` 中间件，并指定允许的源。
        ```javascript
        // server.js
        // const cors = require('cors');
        // app.use(cors({ origin: 'https://your-frontend-domain.com' }));
        ```
    *   **Helmet**: 使用 `helmet` 中间件设置各种 HTTP 安全头部。
        ```javascript
        // server.js
        // const helmet = require('helmet');
        // app.use(helmet());
        ```
    *   **速率限制**: 对 API 请求进行速率限制，防止滥用 (例如使用 `express-rate-limit`)。
    *   **定期更新依赖**: 保持依赖项更新以修复已知的安全漏洞。

## 其他部署方式

1.  **Platform as a Service (PaaS)**:
    *   **Heroku, Render, Vercel (适合 Node.js)** 等平台可以极大地简化部署流程。您只需连接 Git 仓库，平台会自动处理构建、部署和运行环境。
    *   通常通过平台的环境变量设置界面来配置 `MONGODB_URI` 和 `NODE_ENV`。

2.  **Docker 容器化**:
    *   您可以为应用创建一个 `Dockerfile`，将其构建为 Docker 镜像，然后在任何支持 Docker 的环境中运行。
    *   这提供了良好的一致性和隔离性。
    *   示例 `Dockerfile` (基本版):
        ```dockerfile
        FROM node:18-alpine
        WORKDIR /usr/src/app
        COPY package*.json ./
        RUN npm install --production
        COPY . .
        ENV NODE_ENV=production
        ENV PORT=3000 
        # MONGODB_URI 应在容器运行时作为环境变量传入
        EXPOSE ${PORT}
        CMD [ "node", "server.js" ]
        ```
    *   构建镜像: `docker build -t campus-api .`
    *   运行容器: `docker run -d -p 80:3000 --env MONGODB_URI="your_production_mongo_uri" campus-api`

选择最适合您团队技术栈和项目需求的部署方法。

## API 端点

有关 API 端点的详细信息，请参阅相关 API 文档或源代码中的路由定义。
所有端点均以 `/api` 为前缀。例如: `/api/events`, `/api/news`, 等。

---

祝您使用愉快！
