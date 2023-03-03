
function deleteHandle(event) {
         // 一旦フォームをストップ
         //event.preventDefault();
         if(window.confirm('本当によろしいですか?')){
             //消去OKならformを再開
            document.getElementById('delete-form').submit();
         }else{
            alert('キャンセルしました');
         }
     }
