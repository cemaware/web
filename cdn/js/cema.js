
console.log("%cStop right there!\nDon't you know pasting suspicious scripts is dangerous? Don't do it! You're likely to get your account stolen and lose all your cubes! Writing scripts or exploiting them is against Cemaware's terms of service.", "color:#d43636;font-size:18px;");

function alphanumeric(inputtxt) {
  var letters = /^[0-9a-zA-Z_]+$/;
  if (letters.test(inputtxt)) {
    return true;
  } else {
    return false;
  }
}

function isEmail(emailAdress) {
  let regex = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;

  if (emailAdress.match(regex))
    return true;

  else
    return false;
}

