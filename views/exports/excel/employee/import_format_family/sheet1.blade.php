<table>
  <tr>
    <td style="font-size: 18;"><b>IMPORT EMPLOYEE FAMILY</b></td>
  </tr>
  <tr>
    <td style="font-size: 12;"><i>*Format Date: MM/DD/YYYY*</i></td>
  </tr>
</table>

<table>
  <tr>
    <td align="center" width="20" valign="center" rowspan="2" bgcolor="#EEEEEE" style="border: 1px solid #666"
      height="30">
      <b>NIK<span style="color:red">*</span></b>
    </td>
    <td align="center" width="25" valign="center" rowspan="2" bgcolor="#EEEEEE" style="border: 1px solid #666"
      height="30">
      <b>NAME<span style="color:red">*</span></b>
    </td>
    <td align="center" width="25" valign="center" rowspan="2" bgcolor="#EEEEEE" style="border: 1px solid #666"
      height="30">
      <b>BIRTH DATE</b>
    </td>
    <td align="center" width="40" valign="center" colspan="2" bgcolor="#EEEEEE" style="border: 1px solid #666"
      height="30">
      <b>OCCUPATION<span style="color:red">*</span></b>
    </td>
    <td align="center" width="25" valign="center" rowspan="2" bgcolor="#EEEEEE" style="border: 1px solid #666"
      height="30">
      <b>OCCUPATION DESCRIPTION</b>
    </td>
    <td align="center" width="40" valign="center" colspan="2" bgcolor="#EEEEEE" style="border: 1px solid #666"
      height="30">
      <b>RELATION<span style="color:red">*</span></b>
    </td>
    <td align="center" width="30" valign="center" rowspan="2" bgcolor="#EEEEEE" style="border: 1px solid #666"
      height="30">
      <b>ADDRESS</b>
    </td>
    <td align="center" width="20" valign="center" rowspan="2" bgcolor="#EEEEEE" style="border: 1px solid #666"
      height="30">
      <b>PHONE</b>
    </td>
  </tr>
  <tr>
    <td align="center" width="20" valign="center" bgcolor="#EEEEEE" style="border: 1px solid #666" height="30">
      <b>ID</b>
    </td>
    <td align="center" width="30" valign="center" bgcolor="#EEEEEE" style="border: 1px solid #666" height="30">
      <b>NAME</b>
    </td>
    <td align="center" width="20" valign="center" bgcolor="#EEEEEE" style="border: 1px solid #666" height="30">
      <b>ID</b>
    </td>
    <td align="center" width="30" valign="center" bgcolor="#EEEEEE" style="border: 1px solid #666" height="30">
      <b>NAME</b>
    </td>
  </tr>

  @for ($i = 0; $i < 20; $i++)
    <tr>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td>=VLOOKUP(D{{ $i + 6 }},OCCUPATION!$A$3:$B${{ $occupations->count() + 3 }},2,FALSE)</td>
      <td></td>
      <td></td>
      <td>=VLOOKUP(G{{ $i + 6 }},RELATION!$A$3:$B${{ $relations->count() + 3 }},2,FALSE)</td>
      <td></td>
      <td></td>
    </tr>
  @endfor
</table>
