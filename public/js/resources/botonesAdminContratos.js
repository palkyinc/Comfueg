let zona = document.getElementById('zona');
let btnActivar = zona.getElementsByClassName('boton-Alta');
for (let i = 0; i < btnActivar.length; i++) {
    btnActivar[i].addEventListener('click', e => {
        if (!confirm("¿Seguro de Dar Alta?")) {
            e.preventDefault()
        }

    })
}
let btnDesactivar = zona.getElementsByClassName('boton-Baja');
for (let i = 0; i < btnDesactivar.length; i++) {
    btnDesactivar[i].addEventListener('click', e => {
        if (!confirm("¿Seguro de Dar de Baja?")) {
            e.preventDefault()
        }
    })
}