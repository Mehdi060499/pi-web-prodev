import React from "react";
import { useFormik } from "formik";
import {
  Box,
  Button,
  FormControl,
  FormErrorMessage,
  FormLabel,
  Heading,
  Input,
  Textarea,
  VStack,
  Text,
} from "@chakra-ui/react";
import * as Yup from "yup";
import FullScreen from "./FullScreen";
import useSubmit from "./useSubmit";

const Formulaire = () => {
  const { isLoading, submit } = useSubmit();

  const formik = useFormik({
    initialValues: {
      lastName: "",
      firstName: "",
      email: "",
      phone: "",
      type: "",
      comment: "",
    },
    onSubmit: (values) => {
      submit(values.firstName);
      formik.resetForm();
    },
    validationSchema: Yup.object({
      firstName: Yup.string().required("This field is required."),
      lastName: Yup.string().required("This field is required."),
      email: Yup.string()
        .email("Invalid email address")
        .required("This field is required."),
      phone: Yup.string()
        .matches(
          /^[0-9]+$/,
          "le numero de téléphone doit etre composé de chiffre"
        )
         .required("This field is required.")
        .min(8, "le numéro de téléphone doit contenir au moins 8 chiffres")
        .max(15, "le numéro doit avoir moins de 15 chiffres"),
      comment: Yup.string()
        .required("This field is required.")
        .min(25, "Must be at least 25 characters"),
    }),
  });

  return (
    <FullScreen isDarkBackground backgroundColor="#010133" py={16} spacing={8}>
      <VStack w="1024px" p={32} alignItems="flex-start">
        <Heading as="h1" id="contactme-section">
          Nous contacter
        </Heading>
        <Text fontSize={{ base: "lg", md: "xl" }} color="whiteAlpha.700">
          Pour toute question, appelez-nous au : +243823078411 ou envoyez-nous
          un message ci-dessous. Nous vous remercierons pour votre confiance !
        </Text>
        <Box p={6} rounded="md" w="100%">
          <form onSubmit={formik.handleSubmit}>
            <VStack spacing={4}>
              <FormControl
                isInvalid={formik.touched.lastName && formik.errors.lastName}
              >
                <FormLabel htmlFor="lastName">Nom</FormLabel>
                <Input
                  id="lastName"
                  name="lastName"
                  {...formik.getFieldProps("lastName")}
                />{" "}
                <FormErrorMessage>{formik.errors.firstName}</FormErrorMessage>
              </FormControl>
              <FormControl
                isInvalid={formik.touched.firstName && formik.errors.firstName}
              >
                <FormLabel htmlFor="firstName">Prenom</FormLabel>
                <Input
                  id="firstName"
                  name="firstName"
                  {...formik.getFieldProps("firstName")}
                />
                <FormErrorMessage>{formik.errors.firstName}</FormErrorMessage>
              </FormControl>
              <FormControl
                isInvalid={formik.touched.email && formik.errors.email}
              >
                <FormLabel htmlFor="email"> Email</FormLabel>
                <Input
                  id="email"
                  name="email"
                  type="email"
                  {...formik.getFieldProps("email")}
                />
                <FormErrorMessage>{formik.errors.email}</FormErrorMessage>
              </FormControl>
              <FormControl
                isInvalid={formik.touched.phone && formik.errors.phone}
              >
                <FormLabel htmlFor="phone">Numéro de téléphone</FormLabel>
                <Input
                  id="phone"
                  name="phone"
                  type="text"
                  {...formik.getFieldProps("phone")}
                />
                <FormErrorMessage>{formik.errors.phone}</FormErrorMessage>
              </FormControl>
              <FormControl
                isInvalid={formik.touched.comment && formik.errors.comment}
              >
                <FormLabel htmlFor="comment">Laissez nous un message</FormLabel>
                <Textarea
                  id="comment"
                  name="comment"
                  height={250}
                  {...formik.getFieldProps("comment")}
                />
                <FormErrorMessage>{formik.errors.comment}</FormErrorMessage>
              </FormControl>
              <Button
                type="submit"
                colorScheme="yellow"
                width="full"
                isLoading={isLoading}
              >
                Envoyer
              </Button>
            </VStack>
          </form>
        </Box>
      </VStack>
    </FullScreen>
  );
};

export default Formulaire;
