const openModal = document.querySelector(".open-modal"),
modal = document.querySelector(".modal"),
close = document.querySelector(".modal_close");

openModal.onclick = ()=> {
  modal.classList.toggle("is-show");
}

close.onclick = ()=> {
  openModal.click();
}
