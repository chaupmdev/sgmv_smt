<?php
# SGホールディングのリンクから旧サイトのページにアクセスした場合、対応する新しいページにリダイレクトする
# リンクが修正された場合、このファイルは不要になる
header('HTTP/1.1 301 Moved Permanently');
header('Location:/contact/');