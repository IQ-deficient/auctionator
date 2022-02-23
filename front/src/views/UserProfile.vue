<template>
  <div>
    <v-parallax
        height="325"
        src="../assets/Mercy.png"
    >
    </v-parallax>
    <v-card width="50%"
            style="margin: 0 auto; position: relative" color="info">
      <v-row>
        <v-col cols="12" sm="10">
          <div style="position: relative; width: 0; height: 0; margin: 0 auto">
            <div style="position: absolute; top: -120px">
              <v-avatar size="145px">
                <img alt="user" src="../assets/Marc.png">
              </v-avatar>
            </div>
            <a href="#" style="text-decoration: none"><v-icon dark large>
              mdi-pencil
            </v-icon></a>
          </div>
        </v-col>
        <v-col cols="12" sm="2" class="mt-1">
          <v-btn color="accent">Edit</v-btn>
        </v-col>
      </v-row>
      <v-form>
        <v-container>
          <v-row style="margin: 0 auto; width: 90%">
            <v-col cols="12"
                   sm="5"
                   style="margin: 0 auto">
              <v-text-field style="text-align: center"
                  value="marc.thompson"
                  label="Username"
                  solo-inverted
                  shaped
                  dark
                  disabled
              ></v-text-field>
            </v-col>
          </v-row>
          <v-row style="margin: 0 auto; width: 90%">
            <v-col
                cols="12"
                sm="6"
            >
              <v-text-field
                  value="Marc"
                  label="First name"
                  solo-inverted
                  dark
                  readonly
              ></v-text-field>
            </v-col>
            <v-col
                cols="12"
                sm="6"
            >
              <v-text-field
                  value="Thompson"
                  label="Last name"
                  solo-inverted
                  dark
                  readonly
              ></v-text-field>
            </v-col>
          </v-row>
          <v-row style="margin: 0 auto; width: 90%">
            <v-col style="margin: 0 auto"
                cols="12"
                sm="7">
              <v-text-field
                  value="marc.thompson@gmail.com"
                  label="Email"
                  shaped
                  solo-inverted
                  dark
                  disabled
              ></v-text-field>
            </v-col>
          </v-row>
          <v-row style="margin: 0 auto; width: 90%">
            <v-col
                cols="12"
                sm="5">
              <v-select
                  value="Male (Apex)"
                  label="Gender"
                  solo-inverted
                  dark
                  disabled
              ></v-select>
            </v-col>
            <v-col
                cols="12"
                sm="7">
              <v-text-field
                  value="11/27/1998"
                  label="Birthday"
                  solo-inverted
                  dark
                  readonly
              ></v-text-field>
            </v-col>
          </v-row>
          <v-row style="margin: 0 auto; width: 90%">
            <v-col
                cols="12"
                sm="5">
              <v-select
                  value="Montenegro"
                  label="Country"
                  solo-inverted
                  dark
                  disabled
              ></v-select>
            </v-col>
            <v-col
                cols="12"
                sm="7">
              <v-text-field
                  value="+38267816707"
                  label="Phone number"
                  solo-inverted
                  dark
                  readonly
              ></v-text-field>
            </v-col>
          </v-row>
          <v-row style="margin: 0 auto; width: 90%">
            <v-col
                cols="12"
                sm="12">
              <v-btn color="primary"
              >Save</v-btn>
            </v-col>
            <v-col style="position: absolute; right: 0; bottom: 0;"
                cols="12"
                sm="2">
              <v-btn color="accent" class="mb-1"
              >Cancel</v-btn>
            </v-col>
          </v-row>
        </v-container>
      </v-form>
    </v-card>
    </div>
</template>
<script>
export default {
  name: "UserProfile"
}

</script>

<style scoped>

</style>