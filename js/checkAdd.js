import addAlumno from "./addAlumno.js";

const d = document;

export default function checkAdd(e) {
  if (
    !e.target.matches("#alumn-btn-add") &&
    !e.target.matches("#alumn-btn-add *")
  )
    return;

  addAlumno();
  let numAlumno = parseInt(sessionStorage.getItem("numAlumno"));

  if (numAlumno >= 3) {
    d.querySelector(".form-add").style.display = "none";
    return;
  }
}
