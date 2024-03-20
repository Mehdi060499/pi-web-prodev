import React from "react";
import { Box,Grid,GridItem } from "@chakra-ui/react";
import Formulaire from "./formulaire";

const Contact = () => {
  return (
    <>
      <div
        style={{
          height: "100%",
          display: "flex",
          flexDirection: "column",
          alignItems: "center",
          justifyContent: "center",
        }}
      >
        <h1 className="h1-Service">Contact</h1>
        <Box
          borderBottom="15px solid yellow"
          borderRadius="10"
          width="100px"
          mb={4}
          ml={100}
        ></Box>
        <iframe
          title="Ma carte"
          width="100%"
          height="400"
          src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d542.1217846982069!2d15.279854216196972!3d-4.320937322266956!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x1a6a33f4b894bb27%3A0x74484f7cee669b01!2sAvenue%20Milambo%20N%C2%B011!5e0!3m2!1sfr!2stn!4v1703905336477!5m2!1sfr!2stn"
          frameborder="0"
          allowfullscreen
        ></iframe>
      </div>
      <Grid h="750px" templateRows="repeat(2, 1fr)" gap={0}>
        <GridItem colSpan={1}>
          <div style={{ height: "100%" }}>
            <Formulaire />
          </div>
        </GridItem>

      </Grid>
    </>
  );
};

export default footer;
