function setValues() {
    let i = document.form1.select.value;
    window.opener.document.form1.empNo.value = document.form1.empNo_list[i].value;
}