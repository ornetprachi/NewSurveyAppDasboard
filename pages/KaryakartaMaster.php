<?php
include 'Karyakarta.php';

// SELECT 
// km.KK_Cd,KK_Name
// ,dm.Designation_Name,kd.Designation_Cd
// ,Mobile_No_1,km.VidhansabhaNumber,Ward_No,Area,km.VibhagName,Age, Gender,
// km.DataSource,
// CONVERT(varchar,BirthDate,34) as BirthDate,List_No,Voter_Id 
// FROM [103.14.99.154].[MH_CH_WarRoom].dbo.Karyakarta_Master as km 
// INNER JOIN [103.14.99.154].[MH_CH_WarRoom].dbo.Karyakarta_Desig_Details as kd on (km.KK_Cd = kd.KK_Cd)
// INNER JOIN [103.14.99.154].[MH_CH_WarRoom].dbo.Designation_Master as dm on (kd.Designation_Cd = dm.Designation_Cd)
// where km.DataSource is not null 
// order by KK_Name;
?>