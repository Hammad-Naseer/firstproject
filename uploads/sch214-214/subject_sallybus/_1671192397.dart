import 'package:flutter/cupertino.dart';
import 'package:image_picker/image_picker.dart';
import 'package:modal_progress_hud_nsn/modal_progress_hud_nsn.dart';
import 'package:shared_preferences/shared_preferences.dart';
import 'package:flutter/cupertino.dart';
import 'package:flutter/material.dart';
import 'dart:convert';
import 'dart:io';
import 'package:art_sweetalert/art_sweetalert.dart';
import 'package:connectivity_plus/connectivity_plus.dart';
import 'package:date_field/date_field.dart';
import 'package:flutter/material.dart';
import 'package:intl/intl.dart';
import 'package:path_provider/path_provider.dart';
import 'package:shared_preferences/shared_preferences.dart';
import 'common/InternetConnectivity.dart';
import 'package:hexcolor/hexcolor.dart';
import 'common/CommonValues.dart';
import 'package:http/http.dart' as http;
import 'dashboard_screen.dart';
import 'diary_screen.dart';
import 'dart:io';
import 'dart:async';

class RequestLeave extends StatefulWidget {
  const RequestLeave({Key? key}) : super(key: key);

  @override
  State<RequestLeave> createState() => _RequestLeaveState();
}

class _RequestLeaveState extends State<RequestLeave> {
  File? image ;
  final _picker = ImagePicker();
  bool showSpinner = false;

  Future getImage()async
  {
    final pickedFile = await _picker.pickImage(source: ImageSource.gallery, imageQuality: 80);
    if(pickedFile!= null)
      {
        image = File(pickedFile.path);
        setState(() {

        });
      }else{
      print('No image selected!');
    }
  }

  Future<void> uploadImage()async{
    setState(() {
      showSpinner = true;
    });

    var stream = new http.ByteStream(image!.openRead());
    stream.cast();

    var length = await image!.length();
    var uri = Uri.parse('api link');
    var request = new http.MultipartRequest('POST', uri);
    request.fields['title'] = 'static title';

    var multipart = new http.MultipartFile('image', stream, length);

    request.files.add(multipart);

    var response = await request.send();
    if(response.statusCode == 200)
      {
        setState(() {
          showSpinner = false;
        });
        print('uploaded');
      }else{
      setState(() {
        showSpinner = false;
      });
      print('error');
    }
  }

  @override
  Widget build(BuildContext context) {
    return ModalProgressHUD(
      inAsyncCall: showSpinner,
      child: Scaffold(
        body: Column(
          children: [
         Center(

            child: InkWell(
              onTap: ()
              {
                getAttendenceSummary();
              },
              child: Text(
                'Create'
              ),
            ),

          ),
            SizedBox(height: 30,),
            GestureDetector(
              onTap: ()
              {
                getImage();
              },
              child: Text('Pick Image'),
            ),
            SizedBox(height: 150,),
            GestureDetector(
              onTap: (){
                uploadImage();
              },
              child: Container(
                height: 50,
                color: Colors.green,
                child: Text('Upload'),
              ),
            ),
          ],
        ),
      ),
    );
  }


  @override
  void initState() {
    super.initState();


    getPref();

  }
  void getAttendenceSummary() async {


    var headers = {
      'Content-Type': 'application/x-www-form-urlencoded',
    };
    var request = http.Request(
        'POST', Uri.parse(URL + 'mobile_webservices/manage_leaves'));
    request.bodyFields = {
      'operation': 'create',
      'leave_id': '1',
      'start_date': '2022-11-20',
      'end_date': '2022-11-20',
      'reason': 'reason of sick leave:FLU',
      'school_id': '214',
      'school_db': 'indicied_indiciedu_production',
      'student_id': '2',
    };
    request.headers.addAll(headers);
    http.StreamedResponse response = await request.send();

    if (response.statusCode == 200) {
      var result = json.decode(await response.stream.bytesToString());



      print(result);
    }
  }

  void getData(String school_id,String school_db,String student_id) async {


    var headers = {
      'Content-Type': 'application/x-www-form-urlencoded',
    };
    var request = http.Request(
        'POST', Uri.parse(URL + 'mobile_webservices/manage_leaves'));
    request.bodyFields = {
      'school_id': school_id,
      'school_db': school_db,
      'student_id': student_id,
    };
    request.headers.addAll(headers);
    http.StreamedResponse response = await request.send();

    if (response.statusCode == 200) {
      var result = json.decode(await response.stream.bytesToString());



      print(result);
    }
  }
  getPref() async {

    getData('214','indicied_indiciedu_production','2');


  }
}
