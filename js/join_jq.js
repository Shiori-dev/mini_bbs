//厳密なエラーチェック
'use strict';
  $(function(){
  // //nameのチェック
  // //formの入力時にイベントを設定
  //   $("#name").on('input',function(e){

  //     //記入されたニックネームを定数nameに代入
  //     const name = $("#name").val();

  //     //文字数によってメッセージを分岐
  //     if(name.length > 2 && name.length < 13){
  //       $("#message1").text("");
  //     }else{
  //       $("#message1").text("ニックネームは3~12文字で記入してください");
  //     }
  //     });

  // //passwordのチェック
  // //formの入力時にイベントを設定
  //   $("#password").on('input',function(e){
  //     //記入されたパスワードを定数passwordに代入
  //     const password =  $("#password").val();

  //     // passwordの入力数をコンソールでカウント
  //     // console.log( password.length );

  //     //文字数によってメッセージを分岐
  //     if(password.length < 4 ){
  //       $("#message3").text("パスワードは4文字以上で記入してください");
  //     }else{
  //       $("#message3").text("");
  //     }
  //   });

  //   //imageの拡張子チェック
  //   $("#image").on('change' , function(e){

  //     // 画像ファイルを定数fileNameに代入
  //     const image = $("#image").val();

  //     //アップロードを許可する拡張子を設定、imageの拡張子を取得して一致しない場合の処理
  //     if (!image.toUpperCase().match(/\.(jpg|jpeg|png|gif)$/i)){
  //       //「ファイル選択」下にエラーメッセージを表示
  //       $("#message4").text("「.jpg」「.jpeg」「.png」または「.gif」の画像を指定してください");
  //     }
  //   });

  // //formVldとして、formの必須項目の入力チェック処理を設定
  // const formVld = function (){
  //   //formの各項目の値を定数に代入
  //   const name = $("#name").val();
  //   const email = $("#email").val();
  //   const password = $("#password").val();

  //   if(name === ""){
  //       $("#message1").text("ニックネームを入力してください");
  //   }
  //   if(email === ""){
  //       $("#message2").text("メールアドレスを入力してください");
  //       }
  //   if(password=== ""){
  //       $("#message3").text("パスワードを入力してください");
  //       }
  // }

  // //ボタンクリック時に必須項目の入力をチェック
  //   $("#button").on('click',function(e){
  //   //デフォルトのイベントキャンセル
  //   e.preventDefault();
  //   //formVldで設定した処理を実行
  //   formVld();
  //   });

///////////////////非同期通信//////////////////////////////
  $("#button").on('click',function(e){
    //画面のリロードを停止
    e.preventDefault();

    //フォームの値を取得して変数に代入
    // const send_data = {
    //   name : $('#name').val(),
    //   email : $('#email').val(),
    //   password : $('#password').val(),
    //   image : $('#image').val()
    //   }
      //ajax通信開始
      $.ajax({
      //タイプを指定
      type: "POST",
      //接続先URL
      url: "join_jq.php",
      //使用するHTTPメソッド
      data: $("form").serialize(),
    }) //データを受け取ったあとの処理
    .done(function(data){
      var json_data = JSON.parse( data );　
      console.log(json_data);
      //取得した合計金額を表示
      //htmlの各idに出力
      $("#message1").text(json_data.name);
      $("#message2").text(json_data.email);
      $("#message3").text(json_data.password);
      $("#message4").text(json_data.image);
    }).fail( (jqXHR, textStatus, errorThrown) => {
            // Ajax通信が失敗したら発動
            alert('Ajax通信に失敗しました。');
            //コンソールにエラーメッセージを表示
            console.log("ajax通信に失敗しました");
            // HTTPステータスが取得
            console.log("jqXHR          : " + jqXHR.status);
            // タイムアウト、パースエラー
            console.log("textStatus     : " + textStatus);
            // 例外情報
            console.log("errorThrown    : " + errorThrown.message);
          });
  });
  //////////////////////////////////////////////////////////

});
