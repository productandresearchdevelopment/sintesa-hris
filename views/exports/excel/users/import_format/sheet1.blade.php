<table>
  <tr>
    <td style="font-size: 18;"><b>IMPORT USERS</b></td>
  </tr>
</table>

<table>
  <tr>
    <td align="center" width="20" valign="center" rowspan="2" bgcolor="#EEEEEE" style="border: 1px solid #666"
      height="30">
      <b>NAME<span style="color: red">*</span></b>
    </td>
    <td align="center" width="20" valign="center" rowspan="2" bgcolor="#EEEEEE" style="border: 1px solid #666"
      height="30">
      <b>USERNAME</b>
    </td>
    <td align="center" width="20" valign="center" rowspan="2" bgcolor="#EEEEEE" style="border: 1px solid #666"
      height="30">
      <b>EMAIL<span style="color: red">*</span></b>
    </td>
    <td align="center" width="20" valign="center" colspan="2" bgcolor="#EEEEEE" style="border: 1px solid #666"
      height="30">
      <b>ROLE<span style="color: red">*</span></b>
    </td>
    <td align="center" width="20" valign="center" rowspan="2" bgcolor="#EEEEEE" style="border: 1px solid #666"
      height="30">
      <b>PASSWORD<span style="color: red">*</span></b>
    </td>
    <td align="center" width="20" valign="center" colspan="2" bgcolor="#EEEEEE" style="border: 1px solid #666"
      height="30">
      <b>ORGANIZATION<span style="color: red">*</span></b>
    </td>
    <td align="center" width="20" valign="center" colspan="2" bgcolor="#EEEEEE" style="border: 1px solid #666"
      height="30">
      <b>EMPLOYEE<span style="color: red">*</span></b>
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
    <td align="center" width="20" valign="center" bgcolor="#EEEEEE" style="border: 1px solid #666" height="30">
      <b>ID</b>
    </td>
    <td align="center" width="30" valign="center" bgcolor="#EEEEEE" style="border: 1px solid #666" height="30">
      <b>NAME</b>
    </td>
  </tr>

  @for ($i = 0; $i < 20; $i++)
    <tr>
      <td></td> <!-- 1 -->
      <td></td> <!-- 2 -->
      <td></td> <!-- 3 -->
      <td></td> <!-- 4 -->
      <td>=VLOOKUP(D{{ $i + 5 }},ROLES!$A$3:$B${{ $roles->count() + 3 }}, 2, FALSE)</td> <!-- 5 -->
      <td></td> <!-- 6 -->
      <td></td> <!-- 7 -->
      <td>=VLOOKUP(G{{ $i + 5 }},ORGANIZATIONS!$A$3:$B${{ $organizations->count() + 3 }}, 2, FALSE)</td>
      <!-- 8 -->
      <td></td> <!-- 9 -->
      <td>=VLOOKUP(I{{ $i + 5 }},EMPLOYEES!$A$3:$B${{ $employees->count() + 3 }}, 2, FALSE)</td>
      <!-- 10 -->
    </tr>
  @endfor

</table>
