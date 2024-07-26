const d = document;

export default function addAlumno() {
  let numAlumno = parseInt(sessionStorage.getItem("numAlumno"));
  numAlumno++;

  const $templateAlumno = d.getElementById("template-alumno").content,
    $fragmento = d.createDocumentFragment(),
    $formAlumnos = d.getElementById("form-alumnos");

  let $clone = document.importNode($templateAlumno, true);

  $clone.getElementById("grado").setAttribute("id", `grado-${numAlumno}`);
  $clone.getElementById("grupo").setAttribute("id", `grupo-${numAlumno}`);

  let alumn = $clone.getElementById("alumn").textContent;
  $clone.getElementById("alumn").textContent = `${alumn}${numAlumno}`;
  $clone.getElementById("alumn").setAttribute("id", `alumn-${numAlumno}`);

  $clone
    .getElementById("name-alumno")
    .setAttribute("id", `name-alumno-${numAlumno}`);
  $clone
    .getElementById("label-name-alumno")
    .setAttribute("for", `name-alumno-${numAlumno}`);

  $clone
    .getElementById("ap-alumno")
    .setAttribute("id", `ap-alumno-${numAlumno}`);
  $clone
    .getElementById("label-ap-alumno")
    .setAttribute("for", `ap-alumno-${numAlumno}`);

  $clone
    .getElementById("am-alumno")
    .setAttribute("id", `am-alumno-${numAlumno}`);
  $clone
    .getElementById("label-am-alumno")
    .setAttribute("for", `am-alumno-${numAlumno}`);

  $clone
    .getElementById("nia-alumno")
    .setAttribute("id", `nia-alumno-${numAlumno}`);
  $clone
    .getElementById("label-nia-alumno")
    .setAttribute("for", `nia-alumno-${numAlumno}`);

  $fragmento.appendChild($clone);

  $formAlumnos.appendChild($fragmento);

  sessionStorage.setItem("numAlumno", numAlumno);

  var elems = document.querySelectorAll("select");
  var instances = M.FormSelect.init(elems);
}
