/**
 * Override the default yii confirm/alert dialogs, as well
 * as the default confirm/alert dialogs in browser. This function is
 * called by yii when a confirmation is requested.
 *
 * @param string message the message to display
 * @param string ok callback triggered when confirmation is true
 * @param string cancelCallback callback triggered when cancelled
 */
yii.confirm = function (message, okCallback, cancelCallback) {
  if( message.constructor === Array )
  {
   swal({
       html: true, // SweetAlert1
       title: message[0],
       text: message[1],
       //html: message[1], // SweetAlert2
       //confirmButtonColor: '#E80000',
       confirmButtonColor: message[3],
       //type: 'warning',
       type: message[2],
       showCancelButton: true,
       cancelButtonText: 'Avbryt',
       closeOnConfirm: true,
       allowOutsideClick: true
   }, okCallback);
  }
  else
  {
   swal({
       html: true, // SweetAlert1
       title: message,
       type: 'warning',
       showCancelButton: true,
       cancelButtonText: 'Avbryt',
       closeOnConfirm: true,
       allowOutsideClick: true
   }, okCallback);
  }
};

confirm = function (message, okCallback, cancelCallback) {
  if( message.constructor === Array )
  {
    console.log( "hmemmem" );
   swal({
       html: true, // SweetAlert 1
       title: message[0],
       text: message[1],
       //html: message[1], // SweetAlert2
       //confirmButtonColor: '#E80000',
       confirmButtonColor: message[3],
       //type: 'warning',
       type: message[2],
       showCancelButton: true,
       cancelButtonText: 'Avbryt',
       closeOnConfirm: true,
       allowOutsideClick: true
   }, okCallback);
  }
  else
  {
    console.log( okCallback );
   swal({
       html: true, // SweetAlert 1
       title: message,
       type: 'warning',
       showCancelButton: true,
       cancelButtonText: 'Avbryt',
       closeOnConfirm: true,
       allowOutsideClick: true
   }, okCallback);
  }
};

yii.alert = function (message, okCallback, cancelCallback) {
   swal({
       title: message,
       type: 'warning',
       showCancelButton: false,
       closeOnConfirm: true,
       allowOutsideClick: false
   }, okCallback);
};

alert = function (message, okCallback, cancelCallback) {
   swal({
       title: message,
       type: 'warning',
       showCancelButton: false,
       closeOnConfirm: true,
       allowOutsideClick: false
   }, okCallback);
};