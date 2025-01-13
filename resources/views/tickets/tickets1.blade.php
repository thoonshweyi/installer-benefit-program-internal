<html lang="en">
  <head>
    <meta charset="UTF-8">
    <title>Title</title>
    <style>
      table{

        transform: rotate(90deg);
      }
       .page {
          page-break-after:always;
        }
        .last-page{
          width: 612px;
          height: 792px;
          overflow: hidden;
          font-family: Arial, Helvetica;
          position: relative;
          color: #545554;
        }
        .title {
          text-align: center !important;
          border-radius: 15px !important;
        	margin: 1em 20px;
          padding: 10px;
          border: 1px solid #000;
        }
        .text-center {
            text-align: center !important;
            padding-top:10px;
            font-weight: strong;
        }
        .text-center h6{
           color:red;
        }
        h6 {
          font-weight: normal;
        }
        h5 {
          font-weight: normal;
        }
        h4 {
          font-weight: normal;
        }
        h3{
          font-weight: normal;
        }
        .desciption-en {
          font-size: 15px;
        }
        .desciption-mm {
          font-size: 15px;
        }
        .promo-image{
          position: absolute;
          bottom: 0;
          left: 0;
          right: 0;
          height: 50px;
          padding-top:20px;
        }
        td {
          vertical-align: top;
          text-align: left;
        }

    </style>
  </head>
  <body>
    <div class="page">
        <table>
          <tr>
            <td  colspan="3" class="title">
              <h3>{{$customers['promotion_name']}}</h3>
              <h3> Lucky Draw Ticket</h3>
            </td>
          </tr>
          <tr>
              <td style="width: 150px;">
              </td>
              <td style="width: 1px;">
              </td>
              <td style="text-align:right; width: 450px; height:50px">
                  <h6>Ticket No : {{$customers['ticket_nos']}}</h6>
              </td>
          </tr>
          <tr>
            <td style="width: 150px; height:40px">
              <h4>Name</h4>
            </td>
            <td style="width: 1px;">
            <h4>:</h4>
              </td>
            <td style="width: 1px">
              <h4>{{$customers['customer_name']}}</h4>
            </td>
          </tr>
          <tr>
            <td style="width: 150px; height:40px">
              <h4>NRC</h4>
            </td>
            <td style="width: 1px;">
            <h4>:</h4>
              </td>
            <td>
              <h4>{{$customers['nrc']}}</h4>
            </td>
          </tr>
          <tr>
            <td style="width: 150px; height:40px">
              <h4>Phone No</h4>
            </td>
            <td style="width: 1px;">
            <h4>:</h4>
              </td>
            <td>
              <h4>{{$customers['phone_no']}}{{isset($customers['phone_no_2']) ? ', ' . $customers['phone_no_2'] : ''}}</h4>
            </td>
          </tr>
          <tr>
            <td style="width: 150px; height:40px">
              <h4>Date</h4>
            </td>
            <td style="width: 1px;">
            <h4>:</h4>
              </td>
            <td>
              <h4>{{$customers['date']}}</h4>
            </td>
          </tr>
          <tr>
            <td style="width: 150px; height:40px">
              <h4>Invoice No.</h4>
            </td>
            <td style="width: 1px;">
            <h4>:</h4>
              </td>
            <td>
              <h5>{{$customers['invoice_no']}}</h5>
            </td>
          </tr>
        </table>

    </div>
    </body></html>
