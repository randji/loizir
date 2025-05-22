import Image from "next/image";

export default function Home() {
  return (
    <Image
      src="/loizir.jpeg"
      alt="Picture of the author"
      width={144}
      height={144}
      className="w-full h-[100vh] object-cover"
    />
  );
}
