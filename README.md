# attendance-management

## 環境構築

Dockerビルド
1. git clone https://github.com/tsugumi-0406/attendance-management<br>
2. cd attendance-management<br>
3. docker-compose up -d --build

Laravel 環境構築
1. docker-compose exec php bash
2. composer install
3. .env.example ファイルから.envを作成する
4. php artisan key:generate
5. php artisan migrate
6. php artisan db:seed
7. php artisan storage:link

 
## URL
・開発環境 : http://localhost/<br>
・ユーザー登録 : http://localhost/register<br>
・phpMyAdmin : http://localhost:8080/ <br>
・メール認証 : http://localhost:8025/

## 使用技術
・PHP 8.1<br>
・Laravel 8.83.29<br>
・MySQL 8.0.26<br>
・nginx:1.21.1

## その他
※ 環境変数を変更した場合（APP_URL 等）は、以下を実行してください。<br>
php artisan config:clear<br>
php artisan cache:clear<br>

登録されている管理者の情報
メールアドレス：aaa@gmail.com
パスワード：password


## ER図
![ER図](src/index.drawio.png)

