let mainSection = document.getElementById("mainSection");
let body = getElementsByTagName("body");
body.classList.add("flex center");


for (let i=0; i<4; i++) {
	let div = document.createElement("div");
	div.classList.add("flex", "flex-column", "center");
	if (i===1 || i===2) {
		div.classList.add("bigBlock");
	} else {
		div.classList.add("block");
	}
	div.textContent = `Блок номер ${i}`;
	mainSection.append(div);
};

