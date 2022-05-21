function fetch_api(url, metodo, data, callback) {
    let token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    fetch(url, {
        method: metodo,
        credentials: "same-origin",
        headers: {
            "Content-Type": "application/json",
            "Accept": "application/json, text-plain, */*",
            "X-Requested-With": "XMLHttpRequest",
            "X-CSRF-TOKEN": token
        },
        body: JSON.stringify(data),
    })
        .then(response => response.json())
        .then(response => {
            callback(response);
        })
        .catch((error) => {
            console.error('FETCH Volvió con Error:', error);
        })
}
function sarasa() {
    console.log('esto es la funcion sarasa');
}