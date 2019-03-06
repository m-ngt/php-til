function setValues() {
    if ( document.form1.select[0].checked ) {
        // 社員番号を活性
        document.form1.empNo.disabled = false;
        
        // 各項目の値をクリア
        document.form1.empNo.value = '';
        document.form1.empNo2.value = '';
        document.form1.empName.value = '';
        document.form1.dept.value = '';
        document.getElementById('flg').checked = false;
    } else {
        // 社員番号を非活性
        document.form1.empNo.disabled = true;
        // 各項目に値を設定
        let i = document.form1.select.value;
        document.form1.empNo.value = document.form1.empNo_list[i].value;
        document.form1.empNo2.value = document.form1.empNo_list[i].value;
        document.form1.empName.value = document.form1.empName_list[i].value;
        document.form1.dept.value = document.form1.dept_list[i].value;
        if ('0' === document.form1.flg_list[i].value) {
            document.getElementById('flg').checked = false;
        } else {
            document.getElementById('flg').checked = true;
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

function deleteClick(){
    if (confirm('削除を実行しますか?')) {
        document.form1.deleteFlg.value = 1;
    } else {
        alert('キャンセルしました。');
        document.form1.deleteFlg.value = 0;
    }
}