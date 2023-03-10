// chcialem 0 js ale no cusz... ten skrypt i tak nie jest mandatory

const input = document.querySelector(".user__avatar-input");
const img = document.querySelector(".user__avatar");

input.addEventListener("change", changeImg);

function changeImg() {
  if (input.files) {
    const file = input.files[0];
    if (file.type.startsWith("image/")) {
      const newSrc = URL.createObjectURL(file);
      img.setAttribute("src", newSrc);
      URL.revokeObjectURL(newSrc);
    } else {
      input.value = "";
      alert("Dodaj zdjęcie, a nie");
    }
  }
}
