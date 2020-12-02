if (!localStorage.getItem('userAuth')) {
    let result = window.prompt('plz send your auth code')
    localStorage.setItem('userAuth', result);
}
