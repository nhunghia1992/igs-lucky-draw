const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
	return new bootstrap.Tooltip(tooltipTriggerEl)
})

const bgm = document.getElementById("bgm");

function openDialog() {
	var dialog = document.getElementById("dialog");
	dialog.style.display = "flex";
}

function closeDialog() {
	var dialog = document.getElementById("dialog");
	dialog.style.display = "none";
}

function initRandom() {
	$.getJSON("result.json?"+Date.now(), function(result) {
		if (!result || !result.length) return;

		for (i=0; i < result.length; i++) {
			// set prize blocks info
			const id = result[i]
			setWinner(i, id)	
		}

		$('#list_control').addClass('active')
	});
}
initRandom();

function saveResult() {
	const winner_order = []
	$('.prize-block').each(function() {
		const winnerID = $(this).data('winner-id')
		winner_order.push(winnerID)
	})
	$.ajax({
		type: "POST",
		dataType: 'json',
		url: 'save.php',
		data: {winner_order: winner_order},
	});
}

function setWinner(index, id) {
	if (!id) return;

	// set data attr
	const prizeBlock = $('.prize-block').eq(index)
	prizeBlock.data('winner-id', id)
	// get info from blocks
	const block = $(`.block[data-id="${id}"]`)
	const name = block.data('name')
	const phone = block.data('phone')
	const idDisplay = block.text()
	// set prize blocks info
	prizeBlock.find('.prize-id').text(idDisplay)
	prizeBlock.find('.prize-name').text(name)
	prizeBlock.find('.prize-phone').text(phone)
	const img = prizeBlock.data('img')
	prizeBlock.find('.prize-img').attr('src', img)

	// set blocks final class
	$(`.block[data-id="${id}"]`).addClass('final');

	// set dialog info
	const dialog = $('#dialog')
	dialog.find('.dialog-id').text(idDisplay)
	dialog.find('.dialog-name').text(name)
	dialog.find('.dialog-phone').text(phone)
	dialog.find('.dialog-img').attr('src', img)
}

function random(index, steps, interval, region) {
	const ids = [];
	const idsRegion = []
	let blocks = $('.block').not('.final')
	blocks.each(function() {
		const id = $(this).data('id')
		ids.push(id)
	})
	if (region) blocks = blocks.filter(`[data-region="${region}"]`);
	blocks.each(function() {
		const id = $(this).data('id')
		idsRegion.push(id)
	})

	let currentStep = 0
	let highlight = setInterval(function() {
		let randomIndex = null
		while (randomIndex === null || $(`.block[data-id="${ids[randomIndex]}"]`).hasClass('final')) {
			randomIndex = Math.floor((Math.random() * ids.length));
		}
		const randomID = ids[randomIndex]
		const randomBlock = $(`.block[data-id="${randomID}"]`)

		randomBlock.addClass('highlight');
		let timeout = setTimeout(function(){
			randomBlock.removeClass('highlight');
		}, interval);

		currentStep++
		if (currentStep > steps) {
			clearInterval(highlight)
			let randomIndexRegion = null
			while (randomIndexRegion === null || $(`.block[data-id="${idsRegion[randomIndexRegion]}"]`).hasClass('final')) {
				randomIndexRegion = Math.floor((Math.random() * idsRegion.length));
			}
			const randomIDRegion = idsRegion[randomIndexRegion]

			setTimeout(() => {
				setWinner(index, randomIDRegion)
				saveResult()
			}, interval)

			// show congratulations
			setTimeout(openDialog, 3 * interval)
			// openDialog()
		}
	}, interval);
}

$('#start_random').on('click', async function() {
	const currentPrize = $('.prize-block').filter(function() {
		const winnerID = $(this).data('winner-id')
		return winnerID === '' || winnerID === undefined || winnerID === null
	}).first()

	if (!currentPrize.length) return;

	$('.close').addClass('d-none');
	bgm.play();
	
	const index = $('.prize-block').index(currentPrize)
	const region = currentPrize.data('region')
	const steps = 20
	const interval = 300
	const duration = (steps + 3) * interval
	random(index, steps, interval, region)

	setTimeout(() => {
		$('.close').removeClass('d-none');
		bgm.pause();
		bgm.currentTime = 0
	}, duration)
});

$('#clear_list').on('click', function() {
	var passcode = prompt("Please enter passcode");
	if (passcode !== 'abc@123') return;

	$.ajax({
		type: "POST",
		url: 'save.php',
		data: {clear: '1'},
		success: function(response) {
			location.reload();
		}
	});
});
