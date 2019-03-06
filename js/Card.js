function setValues() {
    if ( document.form1.select[0].checked ) {
        // カードIDを活性
        document.form1.cardId.disabled = false;
        // 各項目の値をクリア
        document.form1.cardId.value = '';
        document.form1.cardId2.value = '';
        document.form1.empNo.value = '';
        document.getElementById('flg').checked = false;
    } else {
        // カードIDを非活性
        document.form1.cardId.disabled = true;
        // 各項目に値を設定
        let i = document.form1.select.value;
        document.form1.cardId.value = document.form1.cardId_list[i].value;
        document.form1.cardId2.value = document.form1.cardId_list[i].value;
        document.form1.empNo.value = document.form1.empNo_list[i].value;
        if (document.form1.flg_list[i].value === '0') {
            document.getElementById("flg").checked = false;
        } else {
            document.getElementById("flg").checked = true;
        }
    }
}

function submitClick(){
    if (confirm('登録を実行しますか?')) {
        document.form1.submitFlg.value = 1;
    } else {
        alert('キャンセルしました。');
        document.form1.submitFlg.value = 0;
    }
}

function empList() {
    window.open('empList.php', '', 'width=400,height=600');
}