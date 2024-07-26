import addAlumno from "./addAlumno.js";
import checkAdd from "./checkAdd.js";
import sendForm from "./sendForm.js";

const d = document;

d.addEventListener("click", (e) => {
  checkAdd(e);
});

d.addEventListener("submit", (e) => {
  e.preventDefault();
  console.log(e);
  sendForm(e);
});

d.addEventListener("DOMContentLoaded", (e) => {
  sessionStorage.setItem("numAlumno", 0);
  addAlumno();
});
