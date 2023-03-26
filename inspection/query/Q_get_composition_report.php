<?php
$Sample = $res[0]['Sample'];
// var_dump($Sample);
$get_comp ="SELECT 
Sample
,C_Min
,Si_Min
,Mn_Min
,S_Min
,Cu_Min
,Sn_Min
,Cr_Min
,P_Min
,Zn_Min
,Al_Min
,Ti_Min
,Mg_Min
,Ni_Min
,V_Min
,Mo_Min
,Sb_Min
,Fe1_Min
,Fe2_Min
,C_Max
,Si_Max
,Mn_Max
,S_Max
,Cu_Max
,Sn_Max
,Cr_Max
,P_Max
,Zn_Max
,Al_Max
,Ti_Max
,Mg_Max
,Ni_Max
,V_Max
,Mo_Max
,Sb_Max
,Fe1_Max
,Fe2_Max
,ParamC
,ParamSi
,ParamMn
,ParamS
,ParamCu
,ParamSn
,ParamCr
,ParamP
,ParamZn
,ParamAl
,ParamTi
,ParamMg
,ParamNi
,ParamV
,ParamMo
,ParamSb
,ParamFe1
,ParamFe2
,TitleC
,TitleSi
,TitleMn
,TitleS
,TitleCu
,TitleSn
,TitleCr
,TitleP
,TitleZn
,TitleAl
,TitleTi
,TitleMg
,TitleNi
,TitleV
,TitleMo
,TitleSb
,TitleFe1
,TitleFe2
,CAST(CAST(CMS.Comp_CAct AS numeric(10, 2)) AS float) AS Comp_CAct 
,CAST(CAST(CMS.Comp_SiAct AS numeric(10, 2)) AS float) AS Comp_SiAct
,CAST(CAST(CMS.Comp_MnAct AS numeric(10, 3)) AS float) AS Comp_MnAct
,CAST(CAST(CMS.Comp_SAct AS numeric(10, 3)) AS float) AS Comp_SAct
,CAST(CAST(CMS.Comp_CuAct AS numeric(10, 3)) AS float) AS Comp_CuAct
,CAST(CAST(CMS.Comp_SnAct AS numeric(10, 3)) AS float) AS Comp_SnAct
,CAST(CAST(CMS.Comp_CrAct AS numeric(10, 3)) AS float) AS Comp_CrAct
,CAST(CAST(CMS.Comp_PAct AS numeric(10, 3)) AS float) AS Comp_PAct
,CAST(CAST(CMS.Comp_ZnAct AS numeric(10, 3)) AS float) AS Comp_ZnAct
,CAST(CAST(CMS.Comp_AlAct AS numeric(10, 3)) AS float) AS Comp_AlAct
,CAST(CAST(CMS.Comp_TiAct AS numeric(10, 3)) AS float) AS Comp_TiAct
,CAST(CAST(CMS.Comp_MgAct AS numeric(10, 3)) AS float) AS Comp_MgAct
,CAST(CAST(CMS.Comp_NiAct AS numeric(10, 3)) AS float) AS Comp_NiAct
,CAST(CAST(CMS.Comp_VAct AS numeric(10, 3)) AS float) AS Comp_VAct
,CAST(CAST(CMS.Comp_MoAct AS numeric(10, 3)) AS float) AS Comp_MoAct
,CAST(CAST(CMS.Comp_SbAct AS numeric(10, 3)) AS float) AS Comp_SbAct
,CAST(CAST(CMS.Comp_Fe1 AS numeric(10, 4)) AS float) AS Comp_Fe1
,CAST(CAST(CMS.Comp_Fe2 AS numeric(10, 4)) AS float) AS Comp_Fe2

FROM (
			SELECT 
			Sample as SamplePiv,
			CAST(CAST(SUM(C) AS numeric(10, 3)) AS float) AS C_Min,
			CAST(CAST(SUM(Si) AS numeric(10, 3)) AS float) AS Si_Min,
			CAST(CAST(SUM(Mn) AS numeric(10, 3)) AS float) AS Mn_Min,
			CAST(CAST(SUM(S) AS numeric(10, 3)) AS float) AS S_Min,
			CAST(CAST(SUM(Cu) AS numeric(10, 3)) AS float) AS Cu_Min,
			CAST(CAST(SUM(Sn) AS numeric(10, 3)) AS float) AS Sn_Min,
			CAST(CAST(SUM(Cr) AS numeric(10, 3)) AS float) AS Cr_Min,
			CAST(CAST(SUM(P) AS numeric(10, 3)) AS float) AS P_Min,
			CAST(CAST(SUM(Zn) AS numeric(10, 3)) AS float) AS Zn_Min,
			CAST(CAST(SUM(Al) AS numeric(10, 3)) AS float) AS Al_Min,
			CAST(CAST(SUM(Ti) AS numeric(10, 3)) AS float) AS Ti_Min,
			CAST(CAST(SUM(Mg) AS numeric(10, 3)) AS float) AS Mg_Min,
			CAST(CAST(SUM(Ni) AS numeric(10, 3)) AS float) AS Ni_Min,
			CAST(CAST(SUM(V) AS numeric(10, 3)) AS float) AS V_Min,
			CAST(CAST(SUM(Mo) AS numeric(10, 3)) AS float) AS Mo_Min,
			CAST(CAST(SUM(Sb) AS numeric(10, 3)) AS float) AS Sb_Min,
			CAST(CAST(SUM(Fe1) AS numeric(10, 4)) AS float) AS Fe1_Min,
			CAST(CAST(SUM(Fe2) AS numeric(10, 4)) AS float) AS Fe2_Min,
			CAST(CAST(SUM(maxC) AS numeric(10, 3)) AS float) AS C_Max,
			CAST(CAST(SUM(maxSi) AS numeric(10, 3)) AS float) AS Si_Max,
			CAST(CAST(SUM(maxMn) AS numeric(10, 3)) AS float) AS Mn_Max,
			CAST(CAST(SUM(maxS) AS numeric(10, 3)) AS float) AS S_Max,
			CAST(CAST(SUM(maxCu) AS numeric(10, 3)) AS float) AS Cu_Max,
			CAST(CAST(SUM(maxSn) AS numeric(10, 3)) AS float) AS Sn_Max,
			CAST(CAST(SUM(maxCr) AS numeric(10, 3)) AS float) AS Cr_Max,
			CAST(CAST(SUM(maxP) AS numeric(10, 3)) AS float) AS P_Max,
			CAST(CAST(SUM(maxZn) AS numeric(10, 3)) AS float) AS Zn_Max,
			CAST(CAST(SUM(maxAl) AS numeric(10, 3)) AS float) AS Al_Max,
			CAST(CAST(SUM(maxTi) AS numeric(10, 3)) AS float) AS Ti_Max,
			CAST(CAST(SUM(maxMg) AS numeric(10, 3)) AS float) AS Mg_Max,
			CAST(CAST(SUM(maxNi) AS numeric(10, 3)) AS float) AS Ni_Max,
			CAST(CAST(SUM(maxV) AS numeric(10, 3)) AS float) AS V_Max,
			CAST(CAST(SUM(maxMo) AS numeric(10, 3)) AS float) AS Mo_Max,
			CAST(CAST(SUM(maxSb) AS numeric(10, 3)) AS float) AS Sb_Max,
			CAST(CAST(SUM(maxFe1) AS numeric(10, 4)) AS float) AS Fe1_Max,
			CAST(CAST(SUM(maxFe2) AS numeric(10, 4)) AS float) AS Fe2_Max,
			MAX(ParamC) AS ParamC,
			MAX(ParamSi) AS ParamSi,
			MAX(ParamMn) AS ParamMn,
			MAX(ParamS)	AS ParamS,
			MAX(ParamCu) AS ParamCu,
			MAX(ParamSn) AS ParamSn,
			MAX(ParamCr) AS ParamCr,
			MAX(ParamP)	AS ParamP,
			MAX(ParamZn) AS ParamZn,
			MAX(ParamAl) AS ParamAl,
			MAX(ParamTi) AS ParamTi,
			MAX(ParamMg) AS ParamMg,
			MAX(ParamNi) AS ParamNi,
			MAX(ParamV)	AS ParamV,
			MAX(ParamMo) AS ParamMo,
			MAX(ParamSb) AS ParamSb,
			MAX(ParamFe1) AS ParamFe1,
			MAX(ParamFe2) AS ParamFe2,
			CASE 
				WHEN ( MAX(ParamC) = 'Range' ) THEN CAST(CAST(CAST(SUM(C)  AS numeric(10, 3)) AS float)  AS varchar(50)) + ' - ' + CAST(CAST(CAST(SUM(MaxC)  AS numeric(10, 3)) AS float)  AS varchar(50))
				WHEN ( MAX(ParamC) = 'Max' ) THEN CAST(CAST(CAST(SUM(MaxC)  AS numeric(10, 3)) AS float)  AS varchar(50)) + ' Max'
				WHEN ( MAX(ParamC) = 'Min' ) THEN CAST(CAST(CAST(SUM(C)  AS numeric(10, 3)) AS float)  AS varchar(50)) + ' Min'
				ELSE ''
				END TitleC,
			CASE 
				WHEN ( MAX(ParamSi) = 'Range' ) THEN CAST(CAST(CAST(SUM(Si)  AS numeric(10, 3)) AS float)  AS varchar(50)) + ' - ' + CAST(CAST(CAST(SUM(MaxSi)  AS numeric(10, 3)) AS float)  AS varchar(50))
				WHEN ( MAX(ParamSi) = 'Max' ) THEN CAST(CAST(CAST(SUM(MaxSi)  AS numeric(10, 3)) AS float)  AS varchar(50)) + ' Max'
				WHEN ( MAX(ParamSi) = 'Min' ) THEN CAST(CAST(CAST(SUM(Si)  AS numeric(10, 3)) AS float)  AS varchar(50)) + ' Min'
				ELSE ''
				END TitleSi,
			CASE 
				WHEN ( MAX(ParamMn) = 'Range' ) THEN CAST(CAST(CAST(SUM(Mn)  AS numeric(10, 3)) AS float)  AS varchar(50)) + ' - ' + CAST(CAST(CAST(SUM(MaxMn)  AS numeric(10, 3)) AS float)  AS varchar(50))
				WHEN ( MAX(ParamMn) = 'Max' ) THEN CAST(CAST(CAST(SUM(MaxMn)  AS numeric(10, 3)) AS float)  AS varchar(50)) + ' Max'
				WHEN ( MAX(ParamMn) = 'Min' ) THEN CAST(CAST(CAST(SUM(Mn)  AS numeric(10, 3)) AS float)  AS varchar(50)) + ' Min'
				ELSE ''
				END TitleMn,
			CASE 
				WHEN ( MAX(ParamS) = 'Range' ) THEN CAST(CAST(CAST(SUM(S)  AS numeric(10, 3)) AS float)  AS varchar(50)) + ' - ' + CAST(CAST(CAST(SUM(MaxS)  AS numeric(10, 3)) AS float)  AS varchar(50))
				WHEN ( MAX(ParamS) = 'Max' ) THEN CAST(CAST(CAST(SUM(MaxS)  AS numeric(10, 3)) AS float)  AS varchar(50)) + ' Max'
				WHEN ( MAX(ParamS) = 'Min' ) THEN CAST(CAST(CAST(SUM(S)  AS numeric(10, 3)) AS float)  AS varchar(50)) + ' Min'
				ELSE ''
				END TitleS,
			CASE 
				WHEN ( MAX(ParamCu) = 'Range' ) THEN CAST(CAST(CAST(SUM(Cu)  AS numeric(10, 3)) AS float)  AS varchar(50)) + ' - ' + CAST(CAST(CAST(SUM(MaxCu)  AS numeric(10, 3)) AS float)  AS varchar(50))
				WHEN ( MAX(ParamCu) = 'Max' ) THEN CAST(CAST(CAST(SUM(MaxCu)  AS numeric(10, 3)) AS float)  AS varchar(50)) + ' Max'
				WHEN ( MAX(ParamCu) = 'Min' ) THEN CAST(CAST(CAST(SUM(Cu)  AS numeric(10, 3)) AS float)  AS varchar(50)) + ' Min'
				ELSE ''
				END TitleCu,
			CASE 
				WHEN ( MAX(ParamSn) = 'Range' ) THEN CAST(CAST(CAST(SUM(Sn)  AS numeric(10, 3)) AS float)  AS varchar(50)) + ' - ' + CAST(CAST(CAST(SUM(MaxSn)  AS numeric(10, 3)) AS float)  AS varchar(50))
				WHEN ( MAX(ParamSn) = 'Max' ) THEN CAST(CAST(CAST(SUM(MaxSn)  AS numeric(10, 3)) AS float)  AS varchar(50)) + ' Max'
				WHEN ( MAX(ParamSn) = 'Min' ) THEN CAST(CAST(CAST(SUM(Sn)  AS numeric(10, 3)) AS float)  AS varchar(50)) + ' Min'
				ELSE ''
				END TitleSn,
			CASE 
				WHEN ( MAX(ParamCr) = 'Range' ) THEN CAST(CAST(CAST(SUM(Cr)  AS numeric(10, 3)) AS float)  AS varchar(50)) + ' - ' + CAST(CAST(CAST(SUM(MaxCr)  AS numeric(10, 3)) AS float)  AS varchar(50))
				WHEN ( MAX(ParamCr) = 'Max' ) THEN CAST(CAST(CAST(SUM(MaxCr)  AS numeric(10, 3)) AS float)  AS varchar(50)) + ' Max'
				WHEN ( MAX(ParamCr) = 'Min' ) THEN CAST(CAST(CAST(SUM(Cr)  AS numeric(10, 3)) AS float)  AS varchar(50)) + ' Min'
				ELSE ''
				END TitleCr,
			CASE 
				WHEN ( MAX(ParamP) = 'Range' ) THEN CAST(CAST(CAST(SUM(P)  AS numeric(10, 3)) AS float)  AS varchar(50)) + ' - ' + CAST(CAST(CAST(SUM(MaxP)  AS numeric(10, 3)) AS float)  AS varchar(50))
				WHEN ( MAX(ParamP) = 'Max' ) THEN CAST(CAST(CAST(SUM(MaxP)  AS numeric(10, 3)) AS float)  AS varchar(50)) + ' Max'
				WHEN ( MAX(ParamP) = 'Min' ) THEN CAST(CAST(CAST(SUM(P)  AS numeric(10, 3)) AS float)  AS varchar(50)) + ' Min'
				ELSE ''
				END TitleP,
			CASE 
				WHEN ( MAX(ParamZn) = 'Range' ) THEN CAST(CAST(CAST(SUM(Zn)  AS numeric(10, 3)) AS float)  AS varchar(50)) + ' - ' + CAST(CAST(CAST(SUM(MaxZn)  AS numeric(10, 3)) AS float)  AS varchar(50))
				WHEN ( MAX(ParamZn) = 'Max' ) THEN CAST(CAST(CAST(SUM(MaxZn)  AS numeric(10, 3)) AS float)  AS varchar(50)) + ' Max'
				WHEN ( MAX(ParamZn) = 'Min' ) THEN CAST(CAST(CAST(SUM(Zn)  AS numeric(10, 3)) AS float)  AS varchar(50)) + ' Min'
				ELSE ''
				END TitleZn,
			CASE 
				WHEN ( MAX(ParamAl) = 'Range' ) THEN CAST(CAST(CAST(SUM(Al)  AS numeric(10, 3)) AS float)  AS varchar(50)) + ' - ' + CAST(CAST(CAST(SUM(MaxAl)  AS numeric(10, 3)) AS float)  AS varchar(50))
				WHEN ( MAX(ParamAl) = 'Max' ) THEN CAST(CAST(CAST(SUM(MaxAl)  AS numeric(10, 3)) AS float)  AS varchar(50)) + ' Max'
				WHEN ( MAX(ParamAl) = 'Min' ) THEN CAST(CAST(CAST(SUM(Al)  AS numeric(10, 3)) AS float)  AS varchar(50)) + ' Min'
				ELSE ''
				END TitleAl,
			CASE 
				WHEN ( MAX(ParamTi) = 'Range' ) THEN CAST(CAST(CAST(SUM(Ti)  AS numeric(10, 3)) AS float)  AS varchar(50)) + ' - ' + CAST(CAST(CAST(SUM(MaxTi)  AS numeric(10, 3)) AS float)  AS varchar(50))
				WHEN ( MAX(ParamTi) = 'Max' ) THEN CAST(CAST(CAST(SUM(MaxTi)  AS numeric(10, 3)) AS float)  AS varchar(50)) + ' Max'
				WHEN ( MAX(ParamTi) = 'Min' ) THEN CAST(CAST(CAST(SUM(Ti)  AS numeric(10, 3)) AS float)  AS varchar(50)) + ' Min'
				ELSE ''
				END TitleTi,
			CASE 
				WHEN ( MAX(ParamMg) = 'Range' ) THEN CAST(CAST(CAST(SUM(Mg)  AS numeric(10, 3)) AS float)  AS varchar(50)) + ' - ' + CAST(CAST(CAST(SUM(MaxMg)  AS numeric(10, 3)) AS float)  AS varchar(50))
				WHEN ( MAX(ParamMg) = 'Max' ) THEN CAST(CAST(CAST(SUM(MaxMg)  AS numeric(10, 3)) AS float)  AS varchar(50)) + ' Max'
				WHEN ( MAX(ParamMg) = 'Min' ) THEN CAST(CAST(CAST(SUM(Mg)  AS numeric(10, 3)) AS float)  AS varchar(50)) + ' Min'
				ELSE ''
				END TitleMg,
			CASE 
				WHEN ( MAX(ParamNi) = 'Range' ) THEN CAST(CAST(CAST(SUM(Ni)  AS numeric(10, 3)) AS float)  AS varchar(50)) + ' - ' + CAST(CAST(CAST(SUM(MaxNi)  AS numeric(10, 3)) AS float)  AS varchar(50))
				WHEN ( MAX(ParamNi) = 'Max' ) THEN CAST(CAST(CAST(SUM(MaxNi)  AS numeric(10, 3)) AS float)  AS varchar(50)) + ' Max'
				WHEN ( MAX(ParamNi) = 'Min' ) THEN CAST(CAST(CAST(SUM(Ni)  AS numeric(10, 3)) AS float)  AS varchar(50)) + ' Min'
				ELSE ''
				END TitleNi,
			CASE 
				WHEN ( MAX(ParamV) = 'Range' ) THEN CAST(CAST(CAST(SUM(V)  AS numeric(10, 3)) AS float)  AS varchar(50)) + ' - ' + CAST(CAST(CAST(SUM(MaxV)  AS numeric(10, 3)) AS float)  AS varchar(50))
				WHEN ( MAX(ParamV) = 'Max' ) THEN CAST(CAST(CAST(SUM(MaxV)  AS numeric(10, 3)) AS float)  AS varchar(50)) + ' Max'
				WHEN ( MAX(ParamV) = 'Min' ) THEN CAST(CAST(CAST(SUM(V)  AS numeric(10, 3)) AS float)  AS varchar(50)) + ' Min'
				ELSE ''
				END TitleV,
			CASE 
				WHEN ( MAX(ParamMo) = 'Range' ) THEN CAST(CAST(CAST(SUM(Mo)  AS numeric(10, 3)) AS float)  AS varchar(50)) + ' - ' + CAST(CAST(CAST(SUM(MaxMo)  AS numeric(10, 3)) AS float)  AS varchar(50))
				WHEN ( MAX(ParamMo) = 'Max' ) THEN CAST(CAST(CAST(SUM(MaxMo)  AS numeric(10, 3)) AS float)  AS varchar(50)) + ' Max'
				WHEN ( MAX(ParamMo) = 'Min' ) THEN CAST(CAST(CAST(SUM(Mo)  AS numeric(10, 3)) AS float)  AS varchar(50)) + ' Min'
				ELSE ''
				END TitleMo,
			CASE 
				WHEN ( MAX(ParamSb) = 'Range' ) THEN CAST(CAST(CAST(SUM(Sb)  AS numeric(10, 3)) AS float)  AS varchar(50)) + ' - ' + CAST(CAST(CAST(SUM(MaxSb)  AS numeric(10, 3)) AS float)  AS varchar(50))
				WHEN ( MAX(ParamSb) = 'Max' ) THEN CAST(CAST(CAST(SUM(MaxSb)  AS numeric(10, 3)) AS float)  AS varchar(50)) + ' Max'
				WHEN ( MAX(ParamSb) = 'Min' ) THEN CAST(CAST(CAST(SUM(Sb)  AS numeric(10, 3)) AS float)  AS varchar(50)) + ' Min'
				ELSE ''
				END TitleSb,
			CASE 
				WHEN ( MAX(ParamFe1) = 'Range' ) THEN CAST(CAST(CAST(SUM(Fe1)  AS numeric(10, 3)) AS float)  AS varchar(50)) + ' - ' + CAST(CAST(CAST(SUM(MaxFe1)  AS numeric(10, 3)) AS float)  AS varchar(50))
				WHEN ( MAX(ParamFe1) = 'Max' ) THEN CAST(CAST(CAST(SUM(MaxFe1)  AS numeric(10, 3)) AS float)  AS varchar(50)) + ' Max'
				WHEN ( MAX(ParamFe1) = 'Min' ) THEN CAST(CAST(CAST(SUM(Fe1)  AS numeric(10, 3)) AS float)  AS varchar(50)) + ' Min'
				ELSE ''
				END TitleFe1,
			CASE 
				WHEN ( MAX(ParamFe2) = 'Range' ) THEN CAST(CAST(CAST(SUM(Fe2)  AS numeric(10, 3)) AS float)  AS varchar(50)) + ' - ' + CAST(CAST(CAST(SUM(MaxFe2)  AS numeric(10, 3)) AS float)  AS varchar(50))
				WHEN ( MAX(ParamFe2) = 'Max' ) THEN CAST(CAST(CAST(SUM(MaxFe2)  AS numeric(10, 3)) AS float)  AS varchar(50)) + ' Max'
				WHEN ( MAX(ParamFe2) = 'Min' ) THEN CAST(CAST(CAST(SUM(Fe2)  AS numeric(10, 3)) AS float)  AS varchar(50)) + ' Min'
				END TitleFe2
				
             
			FROM ( 
					SELECT 
						Sample, 
						Param, 
						Element as Element1,
						'max' + Element as Element2,
						'param' + Element as Element3,
						STDMin,
						STDMax
					FROM [QA_INS].[dbo].[QC_INSPECTION_STANDARD] 
					WHERE Sample = '$Sample' AND Category = 'Composition' 
				)SS
				PIVOT
				(
				SUM(STDMin)
				FOR Element1 IN 
				(
					C,
					Si,
					Mn,
					S,
					Cu,
					Sn,
					Cr,
					P,
					Zn,
					Al,
					Ti,
					Mg,
					Ni,
					V,
					Mo,
					Sb,
					Fe1,
					Fe2
				)
			) PIV
			PIVOT
			(
				SUM(STDMax)
				FOR Element2 IN 
				(
					maxC,
					maxSi,
					maxMn,
					maxS,
					maxCu,
					maxSn,
					maxCr,
					maxP,
					maxZn,
					maxAl,
					maxTi,
					maxMg,
					maxNi,
					maxV,
					maxMo,
					maxSb,
					maxFe1,
					maxFe2
				)
			) PIV2
			PIVOT
			(
				Max(Param)
				FOR Element3 IN 
				(
					ParamC,
					ParamSi,
					ParamMn,
					ParamS,
					ParamCu,
					ParamSn,
					ParamCr,
					ParamP,
					ParamZn,
					ParamAl,
					ParamTi,
					ParamMg,
					ParamNi,
					ParamV,
					ParamMo,
					ParamSb,
					ParamFe1,
					ParamFe2
				)
			) PIV3	
			GROUP BY 
			Sample
	) SM
	LEFT JOIN [QA_INS].[dbo].[inspection_lines_cms] CMS ON CMS.Sample = SM.SamplePiv

";
    $stmt_composition = sqlsrv_query($conn, $get_comp);
    while( $row_comp = sqlsrv_fetch_array($stmt_composition, SQLSRV_FETCH_ASSOC) ) {
        $comp[] = $row_comp;
    }
    