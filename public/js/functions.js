function eliminateDoubles(arrSlave,arrMaster) {

  // this function will eliminate entries in the first array
  // that already exist in the second array
  for ( var i=0; i<arrSlave.length; i++ ) {
    for ( var j=0; j<arrMaster.length; j++ ) {
      if ( arrSlave[i] == arrMaster[j] ) {
        arrSlave.splice(i,1)
      }
    }
  }
  return arrSlave
}

function arrayToOptions(arr,Select,value) {

  // builds the content of a select list (given with it's name) from the array provided
  // text of each option will be the homologous array element
  // value of each option will be the same

  $_(Select).options.length = 0

  for (i=0; i < arr.length; i++) {
    $_(Select).options[i] = new Option(arr[i], value)
  }

}

function isStringInArray(String,Array) {

  // checks if a string is element of an array
  // returns true or false

  for (i=0; i < Array.length; i++) {
    if (String == Array[i]) {
      return true
    }
  }

  return false
}

function deleteArrayElement(String,Array) {

  // deletes an element of an array, element given by it's value
  // this means the given String has to match the element to delete exactly

  for (i=0; i < Array.length; i++) {
    if (String == Array[i]) {
      Array.splice(i,1)
    }
  }

}
