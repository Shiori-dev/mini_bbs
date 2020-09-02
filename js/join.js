//厳密なエラーチェック
'use strict';
{

//formにイベントを設定(input:リアルタイムで入力を検知)
  document.querySelector('form').addEventListener('input', e => {
    //e.targetが'input'だったとき、以下を実行
    if (e.target.nodeName === 'INPUT') {
         //フォームのタイプを取得して変数typeに代入
    let type = e.target.type;
    //コンソールに「onInput」とタイプを表記
    console.log('onInput', type);
  }
});

  //id=formを定数formに代入
  const form  = document.getElementById('form');
  //id=messageを定数messageに代入
  const message = document.getElementById('message');
  //ニックネームの文字数制限を設定
  const namePattern = /^[a-zA-Z]{6,12}$/;

  //formの入力完了時にイベントを設定
  form.addEventListener('change', e =>{
    //イベントキャンセル
    e.preventDefault();
    //
    const name = form.name.value;

    if(namePattern.test(name)){
      message.textContent = 'ユーザー名は有効です';
    } else {
      message.textContent = 'ニックネームは6~12文字で記入してください'
    }
  });





}
