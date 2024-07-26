const d = document;

export default async function sendForm(e) {
  if (!e.target.matches("#form-inscription")) return;

  d.querySelector(".load").style.display = "flex";

  const selectTutor = d.getElementById("select-tutor").value,
    nameTutor = d.getElementById("name-tutor").value,
    apTutor = d.getElementById("ap-tutor").value,
    amTutor = d.getElementById("am-tutor").value,
    email = d.getElementById("email").value,
    tel = d.getElementById("tel").value;

  console.log(selectTutor);
  if (selectTutor.value === "") {
    alert("debe Seleccionar si es padre, madre o tutor");
    return;
  }

  let arrAlumnos = [];
  let numAlumnos = parseInt(sessionStorage.getItem("numAlumno"));

  for (let i = 1; i <= numAlumnos; i++) {
    let grado = d.getElementById(`grado-${i}`).value,
      grupo = d.getElementById(`grupo-${i}`).value,
      nameAlumno = d.getElementById(`name-alumno-${i}`).value,
      apAlumno = d.getElementById(`ap-alumno-${i}`).value,
      amAlumno = d.getElementById(`am-alumno-${i}`).value,
      niaAlumno = d.getElementById(`nia-alumno-${i}`).value;

    nameAlumno =
      nameAlumno.charAt(0).toUpperCase() + nameAlumno.slice(1).toLowerCase();
    apAlumno =
      apAlumno.charAt(0).toUpperCase() + apAlumno.slice(1).toLowerCase();
    amAlumno =
      amAlumno.charAt(0).toUpperCase() + amAlumno.slice(1).toLowerCase();

    if (grado === "" || grupo === "") {
      alert(`debe Seleccionar el grado y grupo de los alumno: ${i}`);
      return;
    }

    let alumno = {
      grado,
      grupo,
      nameAlumno,
      apAlumno,
      amAlumno,
      niaAlumno,
    };

    arrAlumnos.push(alumno);
  }

  const formData = new FormData();

  nameTutor =
    nameTutor.charAt(0).toUpperCase() + nameTutor.slice(1).toLowerCase();
  apTutor = apTutor.charAt(0).toUpperCase() + apTutor.slice(1).toLowerCase();
  amTutor = amTutor.charAt(0).toUpperCase() + amTutor.slice(1).toLowerCase();
  email = email.toLowerCase();

  formData.append("parentesco", selectTutor);
  formData.append("nameTutor", nameTutor);
  formData.append("apTutor", apTutor);
  formData.append("amTutor", amTutor);
  formData.append("email", email);
  formData.append("tel", tel);
  formData.append("alumnos", JSON.stringify(arrAlumnos));

  let options = {
    method: "POST",
    body: formData,
  };

  try {
    let res = await fetch(
        "https://cienempresa.com/componentes/assets/send_mail.php",
        options
      ),
      json = await res.json();

    if (json.err) {
      alert(`Ocurrio un error: ${json.statusText}`);
    } else {
      alert("Se han enviado los datos correctamente");
      location.reload;
    }

    //console.log(res, json);

    if (!res.ok) throw { status: res.status, statusText: res.statusText };
  } catch (err) {
    // console.log(err);
    let message = err.statusText || "ocurrio un error";
    alert(message);
  }
}
