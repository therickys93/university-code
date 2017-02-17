FROM ubuntu
RUN apt-get update && apt-get upgrade -y && apt-get install spim -y
ADD . /project
WORKDIR /project
CMD /bin/bash run.sh