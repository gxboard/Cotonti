<!-- BEGIN: MAIN -->

		{FILE ./themes/nemesis/warnings.tpl}

		<div class="block">
			<h2 class="users">{USERS_REGISTER_TITLE}</h2>
			<form name="login" action="{USERS_REGISTER_SEND}" method="post" enctype="multipart/form-data" >
				<table class="list">
					<tr>
						<td class="width30">{PHP.L.Username}:</td>
						<td class="width70">{USERS_REGISTER_USER} *</td>
					</tr>
					<tr>
						<td>{PHP.themelang.usersregister.Validemail}:</td>
						<td>
							{USERS_REGISTER_EMAIL} *
							<p class="small">{PHP.themelang.usersregister.Validemailhint}</p>
						</td>
					</tr>
					<tr>
						<td>{PHP.L.Password}:</td>
						<td>{USERS_REGISTER_PASSWORD} *</td>
					</tr>
					<tr>
						<td>{PHP.themelang.usersregister.Confirmpassword}:</td>
						<td>{USERS_REGISTER_PASSWORDREPEAT} *</td>
					</tr>
					<tr>
						<td>{USERS_REGISTER_VERIFYIMG}</td>
						<td>{USERS_REGISTER_VERIFYINPUT} *</td>
					</tr>
					<tr>
						<td>{PHP.L.Country}:</td>
						<td>{USERS_REGISTER_COUNTRY}</td>
					</tr>
					<tr>
						<td colspan="2">{PHP.themelang.usersregister.Formhint}</td>
					</tr>
					<tr>
						<td colspan="2" class="valid"><input type="submit" value="{PHP.L.Submit}" /></td>
					</tr>
				</table>
			</form>
		</div>

<!-- END: MAIN -->