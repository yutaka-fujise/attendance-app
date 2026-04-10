# Coachtech attendance-app（模擬案件）

## 環境構築

### Docker ビルド

1. リポジトリをクローン

```bash
git clone git@github.com:yutaka-fujise/attendance-app.git
```

2. プロジェクトディレクトリへ移動

```bash
cd attendance-app
```

3. Docker Desktop アプリを起動

4. Docker コンテナをビルド・起動

```bash
docker-compose up -d --build
```

※Mac の M1・M2 チップの PC の場合

no matching manifest for linux/arm64/v8 in the manifest list entries
のエラーが表示され、ビルドできない場合があります。

その際は docker-compose.yml の mysql サービスに
以下の記述を追加してください。

```yaml
mysql:
  platform: linux/x86_64
  image: mysql:8.0.26
```

## Laravel 環境構築

1. PHP コンテナに入る

```bash
docker-compose exec php bash
```

2. パッケージをインストール

```bash
composer install
```

3. .env ファイルを作成

```bash
cp .env.example .env
```

環境によっては .env ファイル編集時に権限エラーが発生する場合があります。
その場合は以下を実行してください。
```bash
chmod 666 .env
```

4. .env のデータベース設定を確認してください
```env
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel_db
DB_USERNAME=laravel_user
DB_PASSWORD=laravel_pass
```

5. アプリケーションキー作成と権限設定

```bash
php artisan key:generate
```

Laravelが書き込みを行うディレクトリの権限設定
```bash
chmod -R 777 storage bootstrap/cache
```


6. マイグレーションとシーディングの実行

```bash
php artisan migrate --seed
```

7. シンボリックリンク作成

```bash
php artisan storage:link
```

## 使用技術（実行環境）

- PHP 8.x
- Laravel 10.x
- MySQL 8.0
- Docker / Docker Compose
- Laravel Fortify

## 機能一覧

### 一般ユーザー
- ユーザー登録
- ログイン / ログアウト
- メール認証
- 出勤打刻
- 休憩開始 / 休憩終了
- 退勤打刻
- 勤怠詳細確認
- 勤怠修正申請
- 勤怠一覧（月次表示）
- 修正申請一覧確認

### 管理者
- 管理者ログイン / ログアウト
- 日次勤怠一覧確認
- 勤怠詳細確認 / 修正
- スタッフ一覧確認
- スタッフ別月次勤怠一覧確認
- 修正申請一覧確認
- 修正申請承認
- CSV出力

## テーブル設計
![テーブル](./table.png)

## ER 図
![ER図](./er.png)

## URL

- 開発環境: http://localhost
- phpMyAdmin: http://localhost:8080/
- ユーザーログイン画面: http://localhost/login
- 管理者ログイン画面: http://localhost/admin/login

## 補足

- ユーザー認証機能には Laravel Fortify を使用しています。

- 一般ユーザーと管理者でログイン導線を分けています。

- 修正申請機能では、申請内容を別テーブルで管理し、承認後に勤怠データへ反映する構成としています。

- Docker 環境下での再現性を重視した構成としています。

## 工夫した点

### 修正申請機能の設計

勤怠データとは別に修正申請用テーブルを設け、承認前のデータと確定データを分離しました。  
これにより、誤ったデータの上書きを防ぎつつ、承認フローを安全に管理できる構成としています。

### 休憩時間の複数管理

1回の勤務に対して複数回の休憩を取れるよう、休憩テーブルを分離し1対多の関係で設計しました。  
単一カラムで管理するのではなく正規化することで、拡張性とデータ整合性を担保しています。

### 管理者と一般ユーザーの権限分離

ユーザーテーブルのroleカラムとミドルウェアを用いて、管理者と一般ユーザーのアクセス制御を実現しました。  
これにより、不正アクセスを防止しつつ、画面ごとの適切な権限管理を行っています。

### UXを意識した画面遷移設計

修正申請の承認後に一覧画面へ戻るのではなく、同一画面にリダイレクトすることで、  
承認状態の変化を即座に確認できるようにしました。

### UIの統一

ラベルと値の位置を揃えるために、gridレイアウトとpaddingを用いてUIを統一しました。  
これにより、視認性と操作性の向上を図りました。