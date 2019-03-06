window.addEventListener('load', function() {
    document.form1.dept.value = document.form1.hdept.value;
    for (let i = 0; i < document.form1.select.length; i++) {
        //  遅刻の活性・非活性 チェック制御
        if (document.form1.elements["late_flg[]"][i].value === '0') {
            if (document.form1.elements["absence_flg[]"][i].value === '1' || document.form1.elements["paid_flg[]"][i].value === '1') {
                document.form1.late[i].disabled = true;
            }
            if (document.form1.in[i].value === '') {
                document.form1.late[i].disabled = true;
            }
        } else {
            document.form1.late[i].checked = true;
        }
        
        //  欠勤の活性・非活性 チェック制御
        if (document.form1.elements["absence_flg[]"][i].value === '0') {
            if (document.form1.in[i].value !== '') {
                document.form1.absence[i].disabled = true;
            }
        } else {
            document.form1.absence[i].checked = true;
        }
        
        //  有休の活性・非活性 チェック制御
        if (document.form1.elements["paid_flg[]"][i].value === '0') {
            if (document.form1.in[i].value !== '') {
                document.form1.paid[i].disabled = true;
            }
        } else {
            document.form1.paid[i].checked = true;
        }
    }
});

function setLateParam() {
    let i = document.form1.select.value;
    if (document.form1.late[i].checked) {
        document.form1.elements["late_flg[]"][i].value = 1;
    } else {
        document.form1.elements["late_flg[]"][i].value = 0;
    }
}

function setAbsenceParam() {
    let i = document.form1.select.value;
    if (document.form1.absence[i].checked) {
        document.form1.elements["absence_flg[]"][i].value = 1;
    } else {
        document.form1.elements["absence_flg[]"][i].value = 0;
    }
}

function setPaidParam() {
    let i = document.form1.select.value;
    if (document.form1.paid[i].checked) {
        document.form1.elements["paid_flg[]"][i].value = 1;
    } else {
        document.form1.elements["paid_flg[]"][i].value = 0;
    }
}

function submitClick(){
    if (confirm('保存を実行しますか?')) {
        document.form1.submitFlg.value = 1;
    } else {
        alert('キャンセルしました。');
        document.form1.submitFlg.value = 0;
    }
}