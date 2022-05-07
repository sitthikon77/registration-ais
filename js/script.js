//  Mobile Number Validation In JavaScript
let check = document.querySelector(".check");
let number = document.querySelector(".number");
let text = document.querySelector(".text");

let regex = /^[\d,\s]{5,20}/;

check.addEventListener("click",()=>{
	if(number.value ==""){
		text.innerText = "โปรดกรอกหมายเลขโทรศัพท์ !";
		text.style.color = "#da3400";
	}
	else if(number.value.length<10){
		text.innerText = "หมายเลขโทรศัพท์ของคุณต้องมี 10 หลัก";
		text.style.color = "#da3400";
	}
    else if(number.value.length>10){
		text.innerText = "หมายเลขโทรศัพท์ของคุณต้องมี 10 หลัก";
		text.style.color = "#da3400";
	}
    else if(number.value.match(regex)){
		text.innerText = "หมายเลขโทรศัพท์ถูกต้อง";
		text.style.color = "rgba(4,125,9,1)";
	}
	else
		{
		text.innerText = "หมายเลขโทรศัพท์ต้องเป็นตัวเลขเท่านั้น";
		text.style.color = "#da3400";
		}
});

// ----------------------------------------------------------------------

(function () {
	'use strict'
  
	// Fetch all the forms we want to apply custom Bootstrap validation styles to
	var forms = document.querySelectorAll('.needs-validation')
  
	// Loop over them and prevent submission
	Array.prototype.slice.call(forms)
	  .forEach(function (form) {
		form.addEventListener('submit', function (event) {
		  if (!form.checkValidity()) {
			event.preventDefault()
			event.stopPropagation()
		  }
  
		  form.classList.add('was-validated')
		}, false)
	  })
  })()