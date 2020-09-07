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
  if(name.length > 2 || name.length < 13){
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
  });

  //nameの入力有無チェックを設定
  const nameVld = function(){
    //記入されたニックネームを定数nameに代入
    const name = form.name.value;
    if(name === ""){
      event.preventDefault();
      smallNode[0].textContent='ニックネームを入力してください';
      return;
    }
  }
  //emailの入力有無チェックを設定
  const emailVld = function(){
    //記入されたメールアドレスを定数emailに代入
    const email = form.email.value;
    if(email === ""){
      event.preventDefault();
      smallNode[1].textContent='メールアドレスを入力してください';
      return;
    }
  }
  //passwordの入力有無チェックを設定
  const passwordVld = function(){
    //記入されたパスワードを定数passwordに代入
    const password = form.password.value;
    if(password === ""){
      event.preventDefault();
      smallNode[2].textContent='パスワードを入力してください';
      return;
    }
  }
  //ボタンが押されたとき、各入力有無のチェックを実行
    formNode.addEventListener("submit", nameVld);
    formNode.addEventListener("submit", emailVld);
    formNode.addEventListener("submit", passwordVld);

/////////////////////////////////////////////////
function sendData( data ) {
    console.log( 'Sending data' );
    // XMLHttpRequestインスタンスの新規作成
    var xhr = new XMLHttpRequest();
    //POSTでjoin.phpと非同期通信を実施
    xhr.open("POST", 'join.php', true);
    //リクエストに従って正しいヘッダー情報を送信
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    //送る値をrequestsに代入
    const requests = {name:"form.name.value;", email:"form.email.value;", password:"form.password.value;", image:"form.image.value;"};

    //通信完了をチェックし、中の処理を実行
    xhr.onload = function(){
    if (this.readyState === XMLHttpRequest.DONE && this.status === 200) {
      // リクエストの終了。ここの処理を実行します
      for (let i =0; i < requests.length; i++){
        console.log(JSON.parse`${requests[i]}`);
      }

    };
  };
  //値を送信
  xhr.send(requests);
}

  //buttonがクリックされたとき、設定した処理を実行
  document.getElementById('button').onclick = function(){
    sendData();
  };
    /////////////////////////////////////////////////////
}
