//厳密なエラーチェック
'use strict';
{
// //formにイベントを設定(input:リアルタイムで入力を検知)
//   document.querySelector('form').addEventListener('input', e => {
//     //e.targetが'input'だったとき、以下を実行
//     if (e.target.nodeName === 'INPUT') {
//          //フォームのタイプを取得して変数typeに代入
//     let type = e.target.type;
//     //コンソールに「onInput」とタイプを表記
//     console.log('onInput', type);
//   }
// });

  //form要素を定数に設定
  const formNode = document.querySelector('form');
  //small要素を定数に設定
  const smallNode = document.querySelectorAll('small');

  //nameのチェック
  //formの入力時にイベントを設定
  formNode[0].addEventListener('input', e =>{
  //デフォルトのイベントキャンセル
  e.preventDefault();

  //記入されたニックネームを定数nameに代入
  const name = form.name.value;

  // nameの入力数をコンソールでカウント
  // console.log( name.length );

  //文字数によってメッセージを分岐
  if(name.length > 2 && name.length < 13){
    smallNode[0].textContent = '';
  }else{
    smallNode[0].textContent = 'ニックネームは3~12文字で記入してください';
  }
  });

//passwordのチェック
  //formの入力時にイベントを設定
  formNode[2].addEventListener('input', e =>{
    //デフォルトのイベントキャンセル
    e.preventDefault();
  //記入されたパスワードを定数passwordに代入
  const password = form.password.value;

    // passwordの入力数をコンソールでカウント
    // console.log( password.length );
    //文字数によってメッセージを分岐
    if(password.length < 4 ){
      smallNode[2].textContent = 'パスワードは4文字以上で記入してください';
    }else{
      smallNode[2].textContent = '';
    }
  });

  //imageの拡張子チェック
  formNode[3].addEventListener('change' , e =>{
   //デフォルトのイベントキャンセル
    e.preventDefault();

  // 画像ファイルを定数fileNameに代入
  const image = document.getElementById('image').value;
  //アップロードを許可する拡張子を設定、imageの拡張子を取得して一致しない場合の処理
  if (!image.toUpperCase().match(/\.(jpg|jpeg|png|gif)$/i)){
    //「ファイル選択」下にエラーメッセージを表示
    smallNode[3].textContent ='「.jpg」「.jpeg」「.png」または「.gif」の画像を指定してください';
  }
  formNode[3].classList.remove('disabled');
  });

  //formVldとして、formの必須項目の入力チェック処理を設定
  const formVld = function (){
    //formの各項目の値を定数に代入
    const name = form.name.value;
    const email = form.email.value;
    const password = form.password.value;

    if(name === ""){
          smallNode[0].textContent='ニックネームを入力してください';
        }

    if(email === ""){
          smallNode[1].textContent='メールアドレスを入力してください';
        }

    if(password === ""){
          smallNode[2].textContent='パスワードを入力してください';
        }
  }

  //ボタンクリック時に必須項目の入力をチェック
  document.getElementById('button').addEventListener('click',e =>
    {
    //デフォルトのイベントキャンセル
    e.preventDefault();
    //formVldで設定した処理を実行
    formVld();
  });


///////////////////非同期通信//////////////////////////////
  //htmlがすべて呼び出されたあと下記を実行
  window.onload = function() {
    // ボタンが押されたらpostに設定した通信を実行
    document.getElementById('button').addEventListener('click',e =>
    {
      //既存のイベントをキャンセル
      e.preventDefault();
      //join.phpにPOST通信を実施
      xhr.open('POST', 'join.php', true);
      //ヘッダーの設定(JSON形式)
      xhr.setRequestHeader('Content-Type', 'application/json');
      // フォームに入力した値をリクエストとして設定
      const requests = {name:formNode[0].value, email:formNode[1].value, password:formNode[2].value, image:formNode[3].value};
      //JSONにエンコード
      const json_text = JSON.stringify(requests);
      //requestの内容を送信
      xhr.send(json_text);
    });

  //XMLHttpRequestインスタンスの新規作成
  const xhr = new XMLHttpRequest();

  //POSTでjoin.phpと非同期通信を実施
  xhr.onload=  function(){
    //通信完了したら以下を実行
    if(xhr.readyState === 4){
      //正常に通信ができたらをレスポンスのテキストを受信
      if(xhr.status === 200){
          //コンソール
          console.log ('通信成功');
        }
      }
    };
  };
  //////////////////////////////////////////////////////////


}
