
## 安裝
### 環境
* PHP >= 7.0
* Laravel 5.2
* Vue.js 2.5
* MariaDB 10.2 (MySQL 5.7)
* composer
* npm
* gulp & Laravel Elixir

### 重建專案

1. 安裝 php 套件
```bash
# command line
composer install
```

2. 安裝 JavaScript 套件
```bash
# command line
npm install
```

3. 設定 .env file
```bash
# command line
cp .env.example .env
php artisan key:generate
```

4. 設定連接資料庫

打開 {project dir}/.env ，找尋下列內容並作設定
```bash
# .env 檔案

# 設定DB driver，不用動
DB_CONNECTION=mysql

# 連接localhost DB
DB_HOST=127.0.0.1

# 連接localhost DB，請跟你環境下mysql所使用的port 相同
DB_PORT=3306

# 設定使用的資料庫名稱
DB_DATABASE={your-dbname}

# 設定資料庫使用者帳號密碼，請替換成你的帳密
DB_USERNAME={your-db-username}
DB_PASSWORD={your-db-password}
```

5. 建立資料庫，進行遷移與資料填充

先透過 mysql 建立一個資料庫，名稱與上面的 .env file 所寫的資料庫名稱相同

編碼設定為 utf-8
![](https://i.imgur.com/8oDveb1.png)

於專案根目錄下依序執行下述內容
```bash
# command line

# 更新 composer autoload
composer dump-autoload

# migrate 資料庫遷移
php artisan migrate

# seed 資料填充
php artisan db:seed
```

6. 啟動開發測試環境
```bash
# command line
php artisan serve

# 預設會是 8080 port，若有需要更換 port 可用下述指令
php artisan serve --port={your-port-number}

# 回應結果
> Laravel development server started on http://localhost:8888/
```
