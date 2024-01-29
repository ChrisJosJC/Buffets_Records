function cambiar() {
	let pdrs = document.getElementById('file').files[0].name;
	len = pdrs.length;
	if (len > 15) {
		str = pdrs.substr(0, 9) + "..." + pdrs.substr(-7);
	} else {
		str = pdrs;
	}
	document.querySelector('#info').innerText = str;
}

const alertPlaceholder = document.getElementById('liveAlertPlaceholder')

const appendAlert = (message, type) => {
	const wrapper = document.createElement('div')
	wrapper.innerHTML = [
		`<div class="alert alert-${type} alert-dismissible" role="alert">`,
		`   <div>${message}</div>`,
		'   <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>',
		'</div>'
	].join('')

	alertPlaceholder.append(wrapper)
}