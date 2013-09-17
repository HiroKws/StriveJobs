StriveJobs
==========

Job management with commands. Without Laravel queue facility.

But now making. So please wait a while.

コマンドとAPIによるジョブ管理Laravel4パッケージ。仕事を時間的に分散させて実行する目的のジョブ管理システムです。

只今、作成中です。GitHubに自分で使用するために置いています。アルファ版です。

### Overview

１．ジョブクラスの登録

StriveJobs\StriveJobsInterfaceを実装し、StriveJobs\BaseJobClassを拡張したクラスを作成する。

~~~
<?php

use StriveJobs\StriveJobsInterface;
use StriveJobs\BaseJobClass;

class BulkMail extends BaseJobClass implements StriveJobsInterface
{

    public function getDescription()
    {
        return 'このジョブクラスの説明';
    }

    public function getName()
    {
        return 'BulkMail'; // ジョブの名前
    }

    public function doRegistered( $data ) // do+ステータスのメソッドを呼び出し
    {
        $this->message = 'おめでとう！'; // メッセージの設定
        $this->setTerminated(); // set+ステータスで、ステータス変更
        return true; // 呼び出し元に、実行の成功を通知
    }

    public function doTerminated( $data ) // ステータスterminatedはジョブ終了
    {
        $this->message = '残念！切腹！'; // コマンドには-vを付けると表示される
        $this->harakiri(); // この実行ジョブを削除
        return true;
    }

    public function doDefault( $data ) // メソッド未定義の場合はこれが呼び出される
    {
        $this->message = '残念！'; // 呼び出し元にメッセージを渡す
        return false; // 呼び出し元に、実行の失敗を通知
    }

}
~~~

２．ジョブクラスのインスタンスを登録する。

~~~
SJ::registerJobClass( array( new BulkMail ) );
SJ::registerJobClass( array( new MonthlyJob ) );
SJ::registerJobClass( array( new WeeklyJob ) );
...
~~~

３．コマンドとAPIが使用できるようになる。

~~~
php artisan sjob:list # 登録したジョブクラス表示
php artisan sjob:register BulkMail --comment "８月２９日送信" # 新ジョブ登録
php artisan sjob:do 7 # 登録済みジョブ実行
~~~

４．多分、ジョブの起動はcronなどのコマンドから使用する。

~~~
php /home/my/project/artisan sjob:auto # 一番古いterminated以外のジョブを一つ実行（未実装）
~~~

５．コマンドと同じようにAPIからも操作できるので、Webページからも管理可能。

### インストール

１．このパッケージをそのうちにpackagistに登録するので、そうしたらcomposer.jsonに登録し、installを実行。

２．app/config/app.phpへサービスプロバイダーを登録する。

~~~
'StriveJobs\StriveJobsServiceProvider',
~~~

３．APIを使用する場合は、エイリアスも登録すると便利。（登録せずとも、IoCコンテナから、キー`StriveJobs\\StriveJobs`でインスタンスを取得可能）

~~~
'SJ' => 'StriveJobs\StriveJobsFacade',
~~~

４．マイグレーションを実行する。（未検証）事前にデータベースの設定が必要とされる。

~~~
php artisan migrate --package hirokws/strivejobs
~~~

５．設定ファイルをローカルにコピーする。

~~~
php artisan config:package hirokws/strivejobs
~~~

６．app/config/packages/hirokws/stirivejobsにconfig.phpが作成される。（多分）

~~~
    // Command main name.
    'MainCommandName' => 'sjob',
    // Hashed reset password. ( Defaut is 'ctrl+shift+del' )
    'HashedResetPassword' => '$2y$08$NsprGibFqvO3B6D7JNjxeOa1u.74pVnPPvSXSI2RJh0630N8XV49q',
~~~

７．MainCommandNameアイテムでコマンド名を指定する。

８．テーブルをtruncateするresetコマンドのパスワードのハッシュを設定する。ハッシュ値はresetコマンドで生成する。（以下はデフォルトのコマンド名、sjobの場合）

~~~
php artisan sjob:reset -?
~~~

#### StriveJobsとは？

VPSも含め、サーバーを自前で立てる場合、もしくは（PHPから見て）外部のキュー、SNSサービスなどのリソースを豊富に利用する場合であれば、この様なジョブ管理は必要ないでしょう

Web上のサービスを利用するにしても、無料使用枠に軽く収まってしまう小さなサービスを同時に２つ思いつきました。必要な機能の一部をこうしたサービスに依存するにしても、処理を時間的に分散させるため、自動起動するジョブの管理が必要になりました。どうせなら、パッケージで作成し、２システム共通で利用しようと作成し始めたのが、このStriveJobsです。

パッケージ名はあの有名だった人と似たような響きの言葉を選びました。"strive jobs"で「仕事を求め、もしくは仕事しようと努力する」という意味です。仕事と格闘している日本人の現状をほのかに織り込んだ名前です。ちなみに私はLinuxユーザーです。


#### What for?

バルクメール配信などを共有サーバーから行うために作成しました。サーバーのリソースを連続して使用する可能性のある作業を時間的に分割して行うための補助パッケージです。
共有サーバーでは、リソースの縛りがあります。PHPの実行時間の制限も規定の最大値以上には上げられません。そうした制限がある場合、連続した作業をダラダラと続けていると、どこかで引っかかってしまいます。安全圏内で動作させる工夫が必要です。

処理を時間的に分けるには、その処理単位を管理しなくてはなりません。何らかの原因で実行が途中で停止したり、異常終了した場合には、そのリカバリー処理も必要になります。

余りにシリアスであれば、そのシステム専用に真面目に設計し、作りこむ必要があります。しかし、バルクメールの送信程度であれば、処理単位毎に状態管理し、それに応じて処理を行える程度の処理ができれば、事足ります。

また、管理するためのコマンドツールを毎回作りこむのも馬鹿らしいからです。

そうした、処理単位をジョブとして管理するパッケージです。

#### ステータス

ジョブが生成されると、

ジョブを登録すると`registered`になります。ジョブの完了状態は`terminated`で表します。それ以外のステータスはお好きに作成できます。

ジョブに起動がかかると、ジョブクラス内の'do'+'ステータス'メソッドが実行されます。該当するメソッドがクラスに存在しない場合、'deDefault'メソッドが呼び出されます。

ステータスは自動では変化しません。ジョブクラス内の呼び出しメソッド内で変更するのが原則です。

ステータスが変更されても、対応するメソッドは自動的に呼び出されません。次回のジョブが起動された時に、その時点のステータスに対応するジョブが呼び出されます。

#### 注意

ジョブは排他制御されません。つまり、同じジョブが同時に複数プロセスで起動される可能性があります。必要であれば、ジョブ内で制御してください。

#### 推定される使用方法（利用シナリオ）

1. ジョブの登録はWebからAPIを通じて登録するか、コマンドで行う。もしくはcronにより、定期的に登録される。
2. ジョブの起動は管理者一人がWebもしくはコマンドから起動するか、cronより定期的に自動的に起動するなど、一箇所からのみ行う。
3. 一回のautoコマンドでは、起動するジョブは一つに指定する。（デフォルト）
4. changeコマンドは非常用。原則はジョブ内部でステータスを変更する。

運用により常に１ジョブだけが走るようにすることで、ジョブの排他制御を避けようという魂胆です。（２，３）

#### コマンド

Laravel4では、コマンドのベースとしてSymfonyライブラリーを利用しているため、コマンドはコマンドとサブコマンドを組み合わせることができます。

デフォルトのコマンド名はsjobですが、設定ファイルで変更可能です。

以下の説明はサブコマンドの説明となります。コマンド名にはデフォルトのsjobを使用しています。

##### register（登録）サブコマンド

ジョブを登録します。

**サンプル**

~~~~
php artisan sjob:register BulkMail
~~~~

BulkMailのジョブクラス名をジョブとして登録します。コメント・引数は空になります。

~~~
php artisan sjob:register BulkMail 101 200  --comment "１０月１０日起動２回目"
~~~

コメントと引数を指定したサンプルです。

**コマンド**

~~~
php artisan sjob:register [--comment[="..."]] job [argument1] [argument2] [argument3] [argument4] [argument5]
~~~

**引数とオプション**

 job                   ジョブクラス名。ジョブクラスのgetNameで返される値を指定する。
 argument1             引数（任意）
 argument2             引数（任意）
 argument3             引数（任意）
 argument4             引数（任意）
 argument5             引数（任意）
 --comment             コメント（任意）

**説明**

起動されるジョブクラスを表す名前を指定し、ジョブを登録します。ステータスは'registered'になります。登録後、そのIDが表示されます。

引数のargument1〜5は以下の形式で、ジョブクラス内のステータスに対応したジョブメソッドに渡されます。

~~~
array(
    'arg1'=>'argument1の引数',
    'arg2'=>'argument1の引数',
    ...
    'arg5'=>'argument5の引数',
);
~~~

##### show（ジョブ表示）サブコマンド

登録済みのジョブを表示します。

**サンプル**

~~~
php artisan sjob:show
~~~

全件を最新順に表示します。

~~~
php artisan sjob:show terminated --oldest -take 10
~~~

ステータスが`terminated`のジョブを最新順に１０件表示します。

**コマンド**

~~~
php artisan sjob:show [-t|--take[="..."]] [-o|--oldest] [status]
~~~

**引数とオプション**
 status                指定されたステータスのジョブのみ表示します。（任意）
 --take (-t)           指定された件数表示します。（任意）
 --oldest (-o)         古いジョブの順番に表示します。（任意）

**説明**

登録済みのジョブを表示します。デフォルトは最新順で全件表示します。

##### do（ジョブ起動）サブコマンド

登録済みのジョブを起動します。

**サンプル**

~~~
php artisan sjob:do 51
~~~

ジョブIDが51番のジョブを起動します。

**コマンド**

php artisan sjob:do id

**引数とオプション**

id                    実行するジョブのIDを一つ指定します。

**説明**

指定されたIDのジョブを起動します。

ジョブが起動されると、その名前が示すジョブクラスの、ステータスに対応したメソッドが呼び出されます。

メソッド名は'do'にステータスの先頭一文字を大文字にした文字列を付けたものです。該当するメソッドが存在しない場合、'doDefault'メソッドが呼び出されます。

呼び出されるメソッドには、ジョブの登録時に一緒に保存された情報（コマンド起動の場合は引数で指定した値の配列）が渡されます。

##### auto（自動起動）サブコマンド

ルールに従い、登録済みのジョブを起動します。（未実装）

**サンプル**
~~~
~~~
**コマンド**
**引数とオプション**
**説明**

##### change（ステータス変更）サブコマンド

ステータスを変更します

**サンプル**

~~~
~~~
**コマンド**
**引数とオプション**
**説明**

##### sweep（終了ジョブ削除）サブコマンド

終了状態のジョブを削除します。

**サンプル**
~~~
~~~
**コマンド**
**引数とオプション**
**説明**

##### reset（管理テーブルリセット）サブコマンド

ジョブ管理テーブルをリセットします。

**サンプル**
~~~
~~~
**コマンド**
**引数とオプション**
**説明**

##### list（登録ジョブクラス表示）サブコマンド

登録済みジョブクラスを一覧表示します。

**サンプル**
~~~
~~~
**コマンド**
**引数とオプション**
**説明**

#### API

#### StriveJobs\BaseJobClassのメソッド



