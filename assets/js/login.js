// mostrar/ocultar senha

const show = document.getElementById('eyeIcon')
show.addEventListener('click', function(){
  const password = document.getElementById('senha')
  
  if(password.type === 'password'){
      password.setAttribute('type','text')
      show.classList.replace('fa-eye','fa-eye-slash')
  }else{
      password.setAttribute('type','password')
      show.classList.replace('fa-eye-slash','fa-eye')
  }
})

// sign in/ sign up
const signInButton = document.querySelector('#signInButton');
const signUpButton = document.querySelector('#signUpButton');
const signIn = document.getElementById('signIn')
const signUp = document.getElementById('signUp')
const btnHover = document.querySelector('.btnHover')
const buttonNextColor = '#004aad';

signUpButton.addEventListener('click', () => {
  signUp.style.display = "block"
  signIn.style.display = "none"  
  btnHover.style.left = "145px"
  btnHover.style.width = "100px"
  let tempColor = signUpButton.style.color;
  signUpButton.style.color = buttonNextColor;
  signInButton.style.color = 'white';
  
})

signInButton.addEventListener('click', () => {
  signIn.style.display = "block"
  signUp.style.display = "none"
  btnHover.style.left = "3px"
  btnHover.style.width = "60px"
  
  let tempColor = signInButton.style.color;
  signInButton.style.color = buttonNextColor;
  signUpButton.style.color = tempColor;
})



const formSignUp = document.getElementById('signUp')
const emailSignUp = document.getElementById('emailSignUp')
const passwordConfirmation = document.getElementById('passwordConfirmation')
const passwordConfirmation2 = document.getElementById('passwordConfirmation2')


// input validation
const inputs = document.querySelectorAll('.inputToValidate');
const spans = document.querySelectorAll('.spans');
const regexEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

function setError(index){
  inputs[index].style.borderBottom = '2px solid rgb(255, 120, 120)'
  spans[index].style.display = 'block'
}
function validate(index){
  inputs[index].style.borderBottom = ''
  spans[index].style.display = 'none'
}

function validarNome(){
  const input = document.getElementById('name')
  input.value = input.value.replace(/[0-9]/, '');
  if(inputs[0].value.length < 3){
    setError(0);
  } else {
    validate(0);
  }
}
function validarEndereco(){
  const input = document.getElementById('address')
  if(inputs[1].value.length < 3){
    setError(1);
  } else {
    validate(1);
  }
}

function validateEmail(){
  if(regexEmail.test(emailSignUp.value) == false){
    setError(2);
  } else {
    validate(2);
  }
}

const helpItems = document.querySelector('.items');
passwordConfirmation.addEventListener('input', keepHelp)

function keepHelp(e){
  if(e){
    helpItems.style.transition = '0.3s'
    helpItems.style.opacity = '1'
    helpItems.style.transform = 'translateX(0%) rotate(0deg)'
  } else {
    helpItems.style.transition = '0.3s'
    helpItems.style.opacity = '0'    
    helpItems.style.transformOrigin = 'top center'
    helpItems.style.transform = 'translateX(-0%) rotate(0deg)'    
  }
}
setInterval(keepHelp, 4000)

function validatePassword(){
  const item = document.querySelectorAll('.item');
  const regexMaiuscula = /[A-Z]/;
  const regexEspecial = /[!@#$%^&*()_+\-=[\]{};':"\\|,.<>/?]+/;

  if(passwordConfirmation.value.length < 8){
    inputs[2].style.borderBottom = '2px solid rgb(255, 120, 120)'
    item[1].style.color = 'rgb(255, 120, 120)'
  } else {
    inputs[2].style.borderBottom = ''
    item[1].style.color = ''
    item[1].style.transition = '0.3s'
  }

  if(regexMaiuscula.test(passwordConfirmation.value) == false){
    inputs[2].style.borderBottom = '2px solid rgb(255, 120, 120)'
    item[2].style.color = 'rgb(255, 120, 120)'
  } else {
    inputs[2].style.borderBottom = ''
    item[2].style.color = ''
    item[2].style.transition = '0.3s'
  }
  
  if(regexEspecial.test(passwordConfirmation.value) == false){
    inputs[2].style.borderBottom = '2px solid rgb(255, 120, 120)'
    item[3].style.color = 'rgb(255, 120, 120)'
  } else {
    inputs[2].style.borderBottom = ''
    item[3].style.color = ''
    item[3].style.transition = '0.3s'
  }


}

function validatePasswordConfirmation(){
  if(passwordConfirmation.value != passwordConfirmation2.value){
    inputs[2].style.borderBottom = '2px solid rgb(255, 120, 120)'
    inputs[3].style.borderBottom = '2px solid rgb(255, 120, 120)'
    spans[2].style.display = 'block'

  } else {
    validate(2);
    validate(3);
  }
}

// mobile 
function adjustPage() {
  var container = document.querySelector('.buttons');
  const tittle = document.querySelector('.container h1')
  var initialHeight = window.innerHeight;

  window.addEventListener('resize', function() {
    var currentHeight = window.innerHeight;
    if (currentHeight < initialHeight) {
      container.style.marginTop = '80px';
      tittle.style.marginTop = '200px'
    } else {
      container.style.marginTop = '';
      tittle.style.marginTop = ''
    }
  });
}
adjustPage();
